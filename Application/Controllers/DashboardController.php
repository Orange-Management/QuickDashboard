<?php

namespace QuickDashboard\Application\Controllers;

use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\Datatypes\SmartDateTime;
use phpOMS\Localization\ISO3166TwoEnum;
use phpOMS\Math\Finance\Lorenzkurve;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Utils\ArrayUtils;
use phpOMS\Views\View;
use QuickDashboard\Application\Models\Queries;
use QuickDashboard\Application\Models\StructureDefinitions;
use QuickDashboard\Application\WebApplication;

class DashboardController
{
    private $app = null;

    const MAX_PAST = 10;

    public function __construct(WebApplication $app)
    {
        $this->app = $app;
    }

    public function showOverview(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/overview');

        $current = new SmartDateTime($request->getData('t') ?? 'now');
        $start   = $this->getFiscalYearStart($current);
        $start->modify('-2 year');

        $totalSales    = [];
        $accTotalSales = [];

        $accounts = StructureDefinitions::ACCOUNTS;
        if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
            $accounts[] = 8591;
        }

        if ($request->getData('u') !== 'gdf') {
            $salesSD = $this->select('selectSalesYearMonth', $start, $current, 'sd', $accounts);
            $this->loopOverview($salesSD, $totalSales);
        }

        if ($request->getData('u') !== 'sd') {
            $salesGDF = $this->select('selectSalesYearMonth', $start, $current, 'gdf', $accounts);
            $this->loopOverview($salesGDF, $totalSales);
        }

        foreach ($totalSales as $year => $months) {
            ksort($totalSales[$year]);

            foreach ($totalSales[$year] as $month => $value) {
                $prev                         = $accTotalSales[$year][$month - 1] ?? 0.0;
                $accTotalSales[$year][$month] = $prev + $value;
            }
        }

        $currentYear  = $current->format('m') - $this->app->config['fiscal_year'] < 0 ? $current->format('Y') - 1 : $current->format('Y');
        $mod          = (int) $current->format('m') - $this->app->config['fiscal_year'];
        $currentMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

        unset($totalSales[$currentYear][$currentMonth]);
        unset($accTotalSales[$currentYear][$currentMonth]);

        $view->setData('currentFiscalYear', $currentYear);
        $view->setData('currentMonth', $currentMonth);
        $view->setData('sales', $totalSales);
        $view->setData('salesAcc', $accTotalSales);
        $view->setData('date', $current);

        return $view;
    }

    private function loopOverview(array $resultset, array &$totalSales)
    {
        foreach ($resultset as $line) {
            $fiscalYear  = $line['months'] - $this->app->config['fiscal_year'] < 0 ? $line['years'] - 1 : $line['years'];
            $mod         = ($line['months'] - $this->app->config['fiscal_year']);
            $fiscalMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

            if (!isset($totalSales[$fiscalYear][$fiscalMonth])) {
                $totalSales[$fiscalYear][$fiscalMonth] = 0.0;
            }

            $totalSales[$fiscalYear][$fiscalMonth] += $line['sales'];
        }
    }

    public function showListMonth(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-list-month');

        $current = new SmartDateTime($request->getData('t') ?? 'now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $current->getStartOfMonth();
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $startLast->getEndOfMonth();

        $totalSales        = [];
        $totalSalesLast    = [];
        $accTotalSales     = [];
        $accTotalSalesLast = [];

        $accounts = StructureDefinitions::ACCOUNTS;
        if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
            $accounts[] = 8591;
        }

        if ($request->getData('u') !== 'gdf') {
            $salesSD     = $this->select('selectSalesDaily', $startCurrent, $endCurrent, 'sd', $accounts);
            $salesSDLast = $this->select('selectSalesDaily', $startLast, $endLast, 'sd', $accounts);

            $this->loopListMonth($salesSD, $totalSales);
            $this->loopListMonth($salesSDLast, $totalSalesLast);
        }

        if ($request->getData('u') !== 'sd') {
            $salesGDFLast = $this->select('selectSalesDaily', $startLast, $endLast, 'gdf', $accounts);
            $salesGDF     = $this->select('selectSalesDaily', $startCurrent, $endCurrent, 'gdf', $accounts);

            $this->loopListMonth($salesGDF, $totalSales);
            $this->loopListMonth($salesGDFLast, $totalSalesLast);
        }

        ksort($totalSales);
        ksort($totalSalesLast);

        $days = $endCurrent->format('d');
        for ($i = 1; $i <= $days; $i++) {
            $prev              = $accTotalSales[$i - 1] ?? 0;
            $accTotalSales[$i] = $prev + ($totalSales[$i] ?? 0);
        }

        $days = $endLast->format('d');
        for ($i = 1; $i <= $days; $i++) {
            $prev                  = $accTotalSalesLast[$i - 1] ?? 0;
            $accTotalSalesLast[$i] = $prev + ($totalSalesLast[$i] ?? 0);
        }

        $view->setData('sales', $totalSales);
        $view->setData('salesAcc', $accTotalSales);
        $view->setData('salesLast', $totalSalesLast);
        $view->setData('salesAccLast', $accTotalSalesLast);
        $view->setData('maxDays', max($endCurrent->format('d'), $endLast->format('d')));
        $view->setData('today', $current->format('d') - 1);
        $view->setData('date', $current);

        return $view;
    }

    private function loopListMonth(array $resultset, array &$totalSales)
    {
        foreach ($resultset as $line) {
            if (!isset($totalSales[$line['days']])) {
                $totalSales[$line['days']] = 0.0;
            }

            $totalSales[$line['days']] += $line['sales'];
        }
    }

    public function showListYear(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-list-year');

        $current = new SmartDateTime($request->getData('t') ?? 'now');
        $start   = $this->getFiscalYearStart($current);
        $start->modify('-1 year');

        $totalSales    = [
            'All' => [],
            'Domestic' => [],
            'Export' => [],
        ];
        $accTotalSales = [
            'All' => [],
            'Domestic' => [],
            'Export' => [],
        ];

        $accounts = StructureDefinitions::ACCOUNTS;
        if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
            $accounts[] = 8591;
        }

        if ($request->getData('u') !== 'gdf') {
            $salesSD = $this->select('selectSalesYearMonth', $start, $current, 'sd', $accounts);
            $this->loopListYear($salesSD, $totalSales);
        }

        if ($request->getData('u') !== 'sd') {
            $salesGDF = $this->select('selectSalesYearMonth', $start, $current, 'gdf', $accounts);
            $this->loopListYear($salesGDF, $totalSales);
        }

        foreach ($totalSales['All'] as $year => $months) {
            ksort($totalSales['All'][$year]);
            ksort($totalSales['Domestic'][$year]);
            ksort($totalSales['Export'][$year]);

            for ($i = 1; $i <= 12; $i++) {
                $prev                     = $accTotalSales['All'][$year][$i - 1] ?? 0.0;
                $accTotalSales['All'][$year][$i] = $prev + ($totalSales['All'][$year][$i] ?? 0);
                $prev                     = $accTotalSales['Domestic'][$year][$i - 1] ?? 0.0;
                $accTotalSales['Domestic'][$year][$i] = $prev + ($totalSales['Domestic'][$year][$i] ?? 0);
                $prev                     = $accTotalSales['Export'][$year][$i - 1] ?? 0.0;
                $accTotalSales['Export'][$year][$i] = $prev + ($totalSales['Export'][$year][$i] ?? 0);
            }
        }

        $currentYear  = $current->format('m') - $this->app->config['fiscal_year'] < 0 ? $current->format('Y') - 1 : $current->format('Y');
        $mod          = (int) $current->format('m') - $this->app->config['fiscal_year'];
        $currentMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

        $view->setData('sales', $totalSales['All'][$currentYear]);
        $view->setData('salesAcc', $accTotalSales['All'][$currentYear]);
        $view->setData('salesLast', $totalSales['All'][$currentYear - 1]);
        $view->setData('salesAccLast', $accTotalSales['All'][$currentYear - 1]);
        $view->setData('salesDomestic', $totalSales['Domestic'][$currentYear]);
        $view->setData('salesAccDomestic', $accTotalSales['Domestic'][$currentYear]);
        $view->setData('salesLastDomestic', $totalSales['Domestic'][$currentYear - 1]);
        $view->setData('salesAccLastDomestic', $accTotalSales['Domestic'][$currentYear - 1]);
        $view->setData('salesExport', $totalSales['Export'][$currentYear]);
        $view->setData('salesAccExport', $accTotalSales['Export'][$currentYear]);
        $view->setData('salesLastExport', $totalSales['Export'][$currentYear - 1]);
        $view->setData('salesAccLastExport', $accTotalSales['Export'][$currentYear - 1]);
        $view->setData('currentFiscalYear', $currentYear);
        $view->setData('currentMonth', $currentMonth);
        $view->setData('date', $current);

        return $view;
    }

    private function loopListYear(array $resultset, array &$totalSales)
    {
        foreach ($resultset as $line) {
            $fiscalYear  = $line['months'] - $this->app->config['fiscal_year'] < 0 ? $line['years'] - 1 : $line['years'];
            $mod         = ($line['months'] - $this->app->config['fiscal_year']);
            $fiscalMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

            if (!isset($totalSales['All'][$fiscalYear][$fiscalMonth])) {
                $totalSales['All'][$fiscalYear][$fiscalMonth] = 0.0;
                $totalSales['Domestic'][$fiscalYear][$fiscalMonth] = 0.0;
                $totalSales['Export'][$fiscalYear][$fiscalMonth] = 0.0;
            }

            $totalSales['All'][$fiscalYear][$fiscalMonth] += $line['sales'];

            $region = StructureDefinitions::getDomesticExportAccount($line['account']);
            $totalSales[$region][$fiscalYear][$fiscalMonth] += $line['sales'];
        }
    }

    public function showLocationMonth(RequestAbstract $request, ResponseAbstract $response)
    {
        $current = new SmartDateTime($request->getData('t') ?? 'now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $current->getStartOfMonth();
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $startLast->getEndOfMonth();

        return $this->showLocation($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
    }

    public function showLocationYear(RequestAbstract $request, ResponseAbstract $response)
    {
        $current = new SmartDateTime($request->getData('t') ?? 'now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $this->getFiscalYearStart($current);
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $endCurrent->createModify(-1);

        return $this->showLocation($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
    }

    public function showLocation(RequestAbstract $request, ResponseAbstract $response, \DateTime $startCurrent, \DateTime $endCurrent, \DateTime $startLast, \DateTime $endLast)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-location');

        $salesRegion         = [];
        $salesDevUndev       = [];
        $salesExportDomestic = [];
        $salesCountry        = [];

        $domesticSD      = [];
        $domesticGDF     = [];
        $domesticSDLast  = [];
        $domesticGDFLast = [];
        $allGDF          = [];
        $allSD           = [];
        $allGDFLast      = [];
        $allSDLast       = [];

        $accounts          = StructureDefinitions::ACCOUNTS;
        $accounts_DOMESTIC = StructureDefinitions::ACCOUNTS_DOMESTIC;
        if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
            $accounts[]          = 8591;
            $accounts_DOMESTIC[] = 8591;
        }

        if ($request->getData('u') !== 'gdf') {
            $countrySD     = $this->select('selectSalesByCountry', $startCurrent, $endCurrent, 'sd', $accounts);
            $countrySDLast = $this->select('selectSalesByCountry', $startLast, $endLast, 'sd', $accounts);

            $this->loopLocation('now', $countrySD, $salesRegion, $salesDevUndev, $salesCountry);
            $this->loopLocation('old', $countrySDLast, $salesRegion, $salesDevUndev, $salesCountry);

            $domesticSDLast = $this->select('selectAccounts', $startLast, $endLast, 'sd', $accounts_DOMESTIC);
            $domesticSD     = $this->select('selectAccounts', $startCurrent, $endCurrent, 'sd', $accounts_DOMESTIC);

            $allSD     = $this->select('selectAccounts', $startCurrent, $endCurrent, 'sd', $accounts);
            $allSDLast = $this->select('selectAccounts', $startLast, $endLast, 'sd', $accounts);
        }

        if ($request->getData('u') !== 'sd') {
            $countryGDF     = $this->select('selectSalesByCountry', $startCurrent, $endCurrent, 'gdf', $accounts);
            $countryGDFLast = $this->select('selectSalesByCountry', $startLast, $endLast, 'gdf', $accounts);

            $this->loopLocation('now', $countryGDF, $salesRegion, $salesDevUndev, $salesCountry);
            $this->loopLocation('old', $countryGDFLast, $salesRegion, $salesDevUndev, $salesCountry);

            $domesticGDFLast = $this->select('selectAccounts', $startLast, $endLast, 'gdf', $accounts_DOMESTIC);
            $domesticGDF     = $this->select('selectAccounts', $startCurrent, $endCurrent, 'gdf', $accounts_DOMESTIC);

            $allGDF     = $this->select('selectAccounts', $startCurrent, $endCurrent, 'gdf', $accounts);
            $allGDFLast = $this->select('selectAccounts', $startLast, $endLast, 'gdf', $accounts);
        }

        $salesExportDomestic['now']['Domestic'] = ($domesticSD[0]['sales'] ?? 0) + ($domesticGDF[0]['sales'] ?? 0);
        $salesExportDomestic['old']['Domestic'] = ($domesticSDLast[0]['sales'] ?? 0) + ($domesticGDFLast[0]['sales'] ?? 0);
        $salesExportDomestic['now']['Export']   = ($allGDF[0]['sales'] ?? 0) + ($allSD[0]['sales'] ?? 0) - $salesExportDomestic['now']['Domestic'];
        $salesExportDomestic['old']['Export']   = ($allGDFLast[0]['sales'] ?? 0) + ($allSDLast[0]['sales'] ?? 0) - $salesExportDomestic['old']['Domestic'];
        $salesCountry['now']['DEU']             = $salesExportDomestic['now']['Domestic'];
        $salesCountry['old']['DEU']             = $salesExportDomestic['old']['Domestic'];

        arsort($salesCountry['now']);

        $salesDevUndev['now']['Developed'] += array_sum($salesExportDomestic['now']) - array_sum($salesDevUndev['now']);
        $salesDevUndev['old']['Developed'] += array_sum($salesExportDomestic['old']) - array_sum($salesDevUndev['old']);

        $salesRegion['now']['Europe'] += array_sum($salesExportDomestic['now']) - array_sum($salesRegion['now']);
        $salesRegion['old']['Europe'] += array_sum($salesExportDomestic['old']) - array_sum($salesRegion['old']);

        $view->setData('salesCountry', $salesCountry);
        $view->setData('salesRegion', $salesRegion);
        $view->setData('salesDevUndev', $salesDevUndev);
        $view->setData('salesExportDomestic', $salesExportDomestic);
        $view->setData('date', $endCurrent);

        return $view;
    }

    private function loopLocation(string $period, array $resultset, array &$salesRegion, array &$salesDevUndev, array &$salesCountry)
    {
        foreach ($resultset as $line) {
            if(!isset($line['countryChar'])) {
                continue;
            }

            $region = StructureDefinitions::getRegion($line['countryChar']);
            if (!isset($salesRegion[$period][$region])) {
                $salesRegion[$period][$region] = 0.0;
            }

            $salesRegion[$period][$region] += $line['sales'];

            $devundev = StructureDefinitions::getDevelopedUndeveloped($line['countryChar']);
            if (!isset($salesDevUndev[$period][$devundev])) {
                $salesDevUndev[$period][$devundev] = 0.0;
            }

            $salesDevUndev[$period][$devundev] += $line['sales'];

            $iso3166Char3 = ltrim(ISO3166TwoEnum::getName(trim(strtoupper($line['countryChar']))), '_');
            if (!isset($salesCountry[$period][$iso3166Char3])) {
                $salesCountry[$period][$iso3166Char3] = 0.0;
            }

            $salesCountry[$period][$iso3166Char3] += $line['sales'];
        }
    }

    public function showArticleMonth(RequestAbstract $request, ResponseAbstract $response)
    {
        $current = new SmartDateTime($request->getData('t') ?? 'now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $current->getStartOfMonth();
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $startLast->getEndOfMonth();

        return $this->showArticle($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
    }

    public function showArticleYear(RequestAbstract $request, ResponseAbstract $response)
    {
        $current = new SmartDateTime($request->getData('t') ?? 'now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $this->getFiscalYearStart($current);
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $endCurrent->createModify(-1);

        return $this->showArticle($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
    }

    public function showArticle(RequestAbstract $request, ResponseAbstract $response, \DateTime $startCurrent, \DateTime $endCurrent, \DateTime $startLast, \DateTime $endLast)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-segmentation');

        $temp = array_slice(StructureDefinitions::NAMING, 0, 6);

        $salesGroups   = [];
        $segmentGroups = [];

        foreach ($temp as $segment) {
            $salesGroups['All'][$segment]   = null;
            $segmentGroups['All'][$segment] = null;

            $salesGroups['Domestic'][$segment]   = null;
            $segmentGroups['Domestic'][$segment] = null;

            $salesGroups['Export'][$segment]   = null;
            $segmentGroups['Export'][$segment] = null;
        }

        $totalGroups = [
            'All'      => ['now' => 0.0, 'old' => 0.0],
            'Domestic' => ['now' => 0.0, 'old' => 0.0],
            'Export'   => ['now' => 0.0, 'old' => 0.0],
        ];

        $accounts = StructureDefinitions::ACCOUNTS;
        if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
            $accounts[] = 8591;
        }

        if ($request->getData('u') !== 'gdf') {
            $groupsSD     = $this->select('selectSalesArticleGroups', $startCurrent, $endCurrent, 'sd', $accounts);
            $groupsSDLast = $this->select('selectSalesArticleGroups', $startLast, $endLast, 'sd', $accounts);

            $this->loopArticleGroups('now', $groupsSD, $salesGroups, $segmentGroups, $totalGroups);
            $this->loopArticleGroups('old', $groupsSDLast, $salesGroups, $segmentGroups, $totalGroups);
        }

        if ($request->getData('u') !== 'sd') {
            $groupsGDF     = $this->select('selectSalesArticleGroups', $startCurrent, $endCurrent, 'gdf', $accounts);
            $groupsGDFLast = $this->select('selectSalesArticleGroups', $startLast, $endLast, 'gdf', $accounts);

            $this->loopArticleGroups('now', $groupsGDF, $salesGroups, $segmentGroups, $totalGroups);
            $this->loopArticleGroups('old', $groupsGDFLast, $salesGroups, $segmentGroups, $totalGroups);
        }

        $view->setData('salesGroups', $salesGroups);
        $view->setData('segmentGroups', $segmentGroups);
        $view->setData('totalGroups', $totalGroups);
        $view->setData('date', $endCurrent);

        return $view;
    }

    private function loopArticleGroups(string $period, array $resultset, array &$salesGroups, array &$segmentGroups, array &$totalGroups)
    {
        foreach ($resultset as $line) {
            $group = StructureDefinitions::getGroupOfArticle($line['costcenter']);

            if ($group === 0) {
                continue;
            }

            $segment = StructureDefinitions::getSegmentOfArticle($line['costcenter']);

            if (!isset(StructureDefinitions::NAMING[$segment]) || !isset(StructureDefinitions::NAMING[$group])) {
                continue;
            }

            /** @noinspection PhpUnreachableStatementInspection */
            if (!isset($salesGroups['All'][StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]][$period])) {
                $salesGroups['All'][StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]][$period]      = 0.0;
                $salesGroups['Domestic'][StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]][$period] = 0.0;
                $salesGroups['Export'][StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]][$period]   = 0.0;
            }

            if (!isset($segmentGroups['All'][StructureDefinitions::NAMING[$segment]][$period])) {
                $segmentGroups['All'][StructureDefinitions::NAMING[$segment]][$period]      = 0.0;
                $segmentGroups['Domestic'][StructureDefinitions::NAMING[$segment]][$period] = 0.0;
                $segmentGroups['Export'][StructureDefinitions::NAMING[$segment]][$period]   = 0.0;
            }

            $salesGroups['All'][StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]][$period] += $line['sales'];
            $segmentGroups['All'][StructureDefinitions::NAMING[$segment]][$period] += $line['sales'];
            $totalGroups['All'][$period] += $line['sales'];

            $region = StructureDefinitions::getDomesticExportAccount($line['account']);
            $salesGroups[$region][StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]][$period] += $line['sales'];
            $segmentGroups[$region][StructureDefinitions::NAMING[$segment]][$period] += $line['sales'];
            $totalGroups[$region][$period] += $line['sales'];
        }
    }

    public function showArticleProfitMonth(RequestAbstract $request, ResponseAbstract $response)
    {
        $current = new SmartDateTime($request->getData('t') ?? 'now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $current->getStartOfMonth();
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $startLast->getEndOfMonth();

        return $this->showArticleProfit($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
    }

    public function showArticleProfitYear(RequestAbstract $request, ResponseAbstract $response)
    {
        $current = new SmartDateTime($request->getData('t') ?? 'now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $this->getFiscalYearStart($current);
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $endCurrent->createModify(-1);

        return $this->showArticleProfit($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
    }

    public function showArticleProfit(RequestAbstract $request, ResponseAbstract $response, \DateTime $startCurrent, \DateTime $endCurrent, \DateTime $startLast, \DateTime $endLast)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Reporting/reporting-profit');

        $temp = array_slice(StructureDefinitions::NAMING, 0, 6);

        $salesGroups   = [];
        $segmentGroups = [];

        foreach ($temp as $segment) {
            $salesGroups['Sales'][$segment]   = null;
            $segmentGroups['Sales'][$segment] = null;

            $salesGroups['Costs'][$segment]   = null;
            $segmentGroups['Costs'][$segment] = null;
        }

        $totalGroups = [
            'Sales' => ['now' => 0.0, 'old' => 0.0],
            'Costs' => ['now' => 0.0, 'old' => 0.0],
        ];

        $accounts      = StructureDefinitions::ACCOUNTS;
        $accountsCosts = StructureDefinitions::ACCOUNTS_COGS;

        $accounts[]      = 8591;
        $accountsCosts[] = 3491;

        if ($request->getData('u') !== 'gdf') {
            $groupsSD     = $this->select('selectSalesArticleGroups', $startCurrent, $endCurrent, 'sd', $accounts);
            $groupsSDLast = $this->select('selectSalesArticleGroups', $startLast, $endLast, 'sd', $accounts);

            $groupsSDCosts     = $this->select('selectSalesArticleGroups', $startCurrent, $endCurrent, 'sd', $accountsCosts);
            $groupsSDCostsLast = $this->select('selectSalesArticleGroups', $startLast, $endLast, 'sd', $accountsCosts);

            $this->loopArticleGroupsProfit('now', $groupsSD, 'Sales', $salesGroups, $segmentGroups, $totalGroups);
            $this->loopArticleGroupsProfit('old', $groupsSDLast, 'Sales', $salesGroups, $segmentGroups, $totalGroups);
            $this->loopArticleGroupsProfit('now', $groupsSDCosts, 'Costs', $salesGroups, $segmentGroups, $totalGroups);
            $this->loopArticleGroupsProfit('old', $groupsSDCostsLast, 'Costs', $salesGroups, $segmentGroups, $totalGroups);
        }

        if ($request->getData('u') !== 'sd') {
            $groupsGDF     = $this->select('selectSalesArticleGroups', $startCurrent, $endCurrent, 'gdf', $accounts);
            $groupsGDFLast = $this->select('selectSalesArticleGroups', $startLast, $endLast, 'gdf', $accounts);

            $groupsGDFCosts     = $this->select('selectSalesArticleGroups', $startCurrent, $endCurrent, 'gdf', $accountsCosts);
            $groupsGDFCostsLast = $this->select('selectSalesArticleGroups', $startLast, $endLast, 'gdf', $accountsCosts);

            $this->loopArticleGroupsProfit('now', $groupsGDF, 'Sales', $salesGroups, $segmentGroups, $totalGroups);
            $this->loopArticleGroupsProfit('old', $groupsGDFLast, 'Sales', $salesGroups, $segmentGroups, $totalGroups);
            $this->loopArticleGroupsProfit('now', $groupsGDFCosts, 'Costs', $salesGroups, $segmentGroups, $totalGroups);
            $this->loopArticleGroupsProfit('old', $groupsGDFCostsLast, 'Costs', $salesGroups, $segmentGroups, $totalGroups);
        }

        $view->setData('salesGroups', $salesGroups);
        $view->setData('segmentGroups', $segmentGroups);
        $view->setData('totalGroups', $totalGroups);
        $view->setData('date', $endCurrent);

        return $view;
    }

    private function loopArticleGroupsProfit(string $period, array $resultset, string $type, array &$salesGroups, array &$segmentGroups, array &$totalGroups)
    {
        foreach ($resultset as $line) {
            $group = StructureDefinitions::getGroupOfArticle((int) ($line['costcenter'] ?? 0));

            if ($group === 0) {
                continue;
            }

            $segment = StructureDefinitions::getSegmentOfArticle((int) ($line['costcenter'] ?? 0));

            if (!isset(StructureDefinitions::NAMING[$segment]) || !isset(StructureDefinitions::NAMING[$group])) {
                continue;
            }

            /** @noinspection PhpUnreachableStatementInspection */
            if (!isset($salesGroups[$type][StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]][$period])) {
                $salesGroups[$type][StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]][$period] = 0.0;
            }

            if (!isset($segmentGroups[$type][StructureDefinitions::NAMING[$segment]][$period])) {
                $segmentGroups[$type][StructureDefinitions::NAMING[$segment]][$period] = 0.0;
            }

            $salesGroups[$type][StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]][$period] += $line['sales'];
            $segmentGroups[$type][StructureDefinitions::NAMING[$segment]][$period] += $line['sales'];
            $totalGroups[$type][$period] += $line['sales'];
        }

    }

    public function showRepsMonth(RequestAbstract $request, ResponseAbstract $response)
    {
        $current = new SmartDateTime($request->getData('t') ?? 'now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $current->getStartOfMonth();
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $startLast->getEndOfMonth();

        return $this->showReps($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
    }

    public function showRepsYear(RequestAbstract $request, ResponseAbstract $response)
    {
        $current = new SmartDateTime($request->getData('t') ?? 'now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $this->getFiscalYearStart($current);
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $endCurrent->createModify(-1);

        return $this->showReps($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
    }

    public function showReps(RequestAbstract $request, ResponseAbstract $response, \DateTime $startCurrent, \DateTime $endCurrent, \DateTime $startLast, \DateTime $endLast)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-reps');

        $accounts = StructureDefinitions::ACCOUNTS;
        if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
            $accounts[] = 8591;
        }

        if ($request->getData('u') !== 'gdf') {
            $repsSD     = $this->select('selectSalesRep', $startCurrent, $endCurrent, 'sd', $accounts);
            $repsSDLast = $this->select('selectSalesRep', $startLast, $endLast, 'sd', $accounts);

            foreach ($repsSD as $line) {
                $repsSales[$line['rep']]['now'] = $line['sales'];
            }

            foreach ($repsSDLast as $line) {
                $repsSales[$line['rep']]['old'] = $line['sales'];
            }
        }

        arsort($repsSales);

        $view->setData('repsSales', $repsSales);
        $view->setData('date', $endCurrent);

        return $view;
    }

    public function showCustomersMonth(RequestAbstract $request, ResponseAbstract $response)
    {
        $current = new SmartDateTime($request->getData('t') ?? 'now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $current->getStartOfMonth();
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $startLast->getEndOfMonth();

        return $this->showCustomers($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
    }

    public function showCustomersYear(RequestAbstract $request, ResponseAbstract $response)
    {
        $current = new SmartDateTime($request->getData('t') ?? 'now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $this->getFiscalYearStart($current);
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $endCurrent->createModify(-1);

        return $this->showCustomers($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
    }

    public function showCustomers(RequestAbstract $request, ResponseAbstract $response, \DateTime $startCurrent, \DateTime $endCurrent, \DateTime $startLast, \DateTime $endLast)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-customer');

        $current      = new SmartDateTime($request->getData('t') ?? 'now');
        $currentYear  = $current->format('m') - $this->app->config['fiscal_year'] < 0 ? $current->format('Y') - 1 : $current->format('Y');
        $mod          = (int) $current->format('m') - $this->app->config['fiscal_year'];
        $currentMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;
        $start        = $this->getFiscalYearStart($current);
        $start->modify('-2 year');

        $salesGroups    = [];
        $totalGroups    = ['now' => 0.0, 'old' => 0.0];
        $salesCustomers = [];
        $customerCount  = [];

        $accounts = StructureDefinitions::ACCOUNTS;
        if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
            $accounts[] = 8591;
        }

        if ($request->getData('u') !== 'gdf') {
            $groupsSD        = $this->select('selectCustomerGroup', $startCurrent, $endCurrent, 'sd', $accounts);
            $groupsSDLast    = $this->select('selectCustomerGroup', $startLast, $endLast, 'sd', $accounts);
            $customersSD     = $this->select('selectCustomer', $startCurrent, $endCurrent, 'sd', $accounts);
            $customersSDLast = $this->select('selectCustomer', $startLast, $endLast, 'sd', $accounts);

            $this->loopCustomerGroups('now', $groupsSD, 'sd', $salesGroups, $totalGroups);
            $this->loopCustomer('now', $customersSD, $salesCustomers);
            $this->loopCustomerGroups('old', $groupsSDLast, 'sd', $salesGroups, $totalGroups);
            $this->loopCustomer('old', $customersSDLast, $salesCustomers);

            $customerSD = $this->select('selectCustomerCount', $start, $current, 'sd', $accounts);
            $this->loopCustomerCount($customerSD, $customerCount);
        }

        if ($request->getData('u') !== 'sd') {
            $groupsGDF        = $this->select('selectCustomerGroup', $startCurrent, $endCurrent, 'gdf', $accounts);
            $groupsGDFLast    = $this->select('selectCustomerGroup', $startLast, $endLast, 'gdf', $accounts);
            $customersGDF     = $this->select('selectCustomer', $startCurrent, $endCurrent, 'gdf', $accounts);
            $customersGDFLast = $this->select('selectCustomer', $startLast, $endLast, 'gdf', $accounts);

            $this->loopCustomerGroups('now', $groupsGDF, 'gdf', $salesGroups, $totalGroups);
            $this->loopCustomer('now', $customersGDF, $salesCustomers);
            $this->loopCustomerGroups('old', $groupsGDFLast, 'gdf', $salesGroups, $totalGroups);
            $this->loopCustomer('old', $customersGDFLast, $salesCustomers);

            $customerGDF = $this->select('selectCustomerCount', $start, $current, 'gdf', $accounts);
            $this->loopCustomerCount($customerGDF, $customerCount);
        }

        arsort($salesCustomers['now']);
        arsort($salesCustomers['old']);

        $gini = [
            'now' => Lorenzkurve::getGiniCoefficient($salesCustomers['now']),
            'old' => Lorenzkurve::getGiniCoefficient($salesCustomers['old']),
        ];

        foreach ($customerCount as $year => $months) {
            ksort($customerCount[$year]);
        }

        $view->setData('salesGroups', $salesGroups);
        $view->setData('totalGroups', $totalGroups);
        $view->setData('customer', $salesCustomers);
        $view->setData('currentFiscalYear', $currentYear);
        $view->setData('customerCount', $customerCount);
        $view->setData('gini', $gini);
        $view->setData('date', $endCurrent);

        return $view;
    }

    private function loopCustomerCount(array $resultset, array &$customerCount)
    {
        foreach ($resultset as $line) {
            $fiscalYear  = $line['months'] - $this->app->config['fiscal_year'] < 0 ? $line['years'] - 1 : $line['years'];
            $mod         = ($line['months'] - $this->app->config['fiscal_year']);
            $fiscalMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

            if (!isset($customerCount[$fiscalYear][$fiscalMonth])) {
                $customerCount[$fiscalYear][$fiscalMonth] = 0;
            }

            $customerCount[$fiscalYear][$fiscalMonth] += $line['customers'];
        }
    }

    private function loopCustomerGroups(string $period, array $resultset, string $company, array &$salesGroups, array &$totalGroups)
    {
        foreach ($resultset as $line) {
            if (!isset(StructureDefinitions::CUSTOMER_GROUP[$company][$line['cgroup']])) {
                continue;
            }

            $customerGroup = StructureDefinitions::CUSTOMER_GROUP[$company][$line['cgroup']];
            if (!isset($salesGroups[$customerGroup][$period])) {
                $salesGroups[$customerGroup][$period] = 0.0;
            }

            $salesGroups[$customerGroup][$period] += $line['sales'];
            $totalGroups[$period] += $line['sales'];
        }
    }

    private function loopCustomer(string $period, array $resultset, array &$salesCustomers)
    {
        foreach ($resultset as $line) {
            $customer = trim($line['customer']);
            if (!isset($salesCustomers[$period][$customer])) {
                $salesCustomers[$period][$customer] = 0.0;
            }

            $salesCustomers[$period][$customer] += $line['sales'];
        }
    }

    public function showAnalysisReps(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Analysis/analysis-reps');

        return $view;
    }

    public function showPLMonth(RequestAbstract $request, ResponseAbstract $response)
    {
        $current = new SmartDateTime($request->getData('t') ?? 'now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $current->getStartOfMonth();
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $startLast->getEndOfMonth();

        return $this->showPL($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
    }

    public function showPLYear(RequestAbstract $request, ResponseAbstract $response)
    {
        $current = new SmartDateTime($request->getData('t') ?? 'now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $this->getFiscalYearStart($current);
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $endCurrent->createModify(-1);

        return $this->showPL($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
    }

    public function showPL(RequestAbstract $request, ResponseAbstract $response, \DateTime $startCurrent, \DateTime $endCurrent, \DateTime $startLast, \DateTime $endLast)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Reporting/pl');

        $accountPositions = [];
        $accounts         = ArrayUtils::arrayFlatten(StructureDefinitions::PL_ACCOUNTS);
        if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
            $accounts[] = 8591;
            $accounts[] = 3491;
        }

        if ($request->getData('u') !== 'gdf') {
            $accountsSD     = $this->select('selectEntries', $startCurrent, $endCurrent, 'sd', $accounts);
            $accountsSDLast = $this->select('selectEntries', $startLast, $endLast, 'sd', $accounts);

            $this->loopPL('now', $accountsSD, $accountPositions);
            $this->loopPL('old', $accountsSDLast, $accountPositions);
        }

        if ($request->getData('u') !== 'sd') {
            $accountsGDF     = $this->select('selectEntries', $startCurrent, $endCurrent, 'gdf', $accounts);
            $accountsGDFLast = $this->select('selectEntries', $startLast, $endLast, 'gdf', $accounts);

            $this->loopPL('now', $accountsGDF, $accountPositions);
            $this->loopPL('old', $accountsGDFLast, $accountPositions);
        }

        $accountPositions['Gross Profit']['now']        = ($accountPositions['Sales']['now'] ?? 0) + ($accountPositions['COGS Material']['now'] ?? 0) + ($accountPositions['COGS Services']['now'] ?? 0);
        $accountPositions['Gross Profit']['old']        = ($accountPositions['Sales']['old'] ?? 0) + ($accountPositions['COGS Material']['old'] ?? 0) + ($accountPositions['COGS Services']['old'] ?? 0);
        $accountPositions['Gross Profit Margin']['now'] = ($accountPositions['Gross Profit']['now'] ?? 0) / ($accountPositions['Sales']['now'] ?? 0);
        $accountPositions['Gross Profit Margin']['old'] = ($accountPositions['Gross Profit']['old'] ?? 0) / ($accountPositions['Sales']['old'] ?? 0);

        $accountPositions['Other Selling Expenses']['now']        = ($accountPositions['Freight']['now'] ?? 0) + ($accountPositions['Provisions']['now'] ?? 0) + ($accountPositions['External Seminars']['now'] ?? 0);
        $accountPositions['Other Selling Expenses']['old']        = ($accountPositions['Freight']['old'] ?? 0) + ($accountPositions['Provisions']['old'] ?? 0) + ($accountPositions['External Seminars']['old'] ?? 0);
        $accountPositions['Other Selling Expenses Margin']['now'] = ($accountPositions['Other Selling Expenses']['now'] ?? 0) / ($accountPositions['Sales']['now'] ?? 0);
        $accountPositions['Other Selling Expenses Margin']['old'] = ($accountPositions['Other Selling Expenses']['old'] ?? 0) / ($accountPositions['Sales']['old'] ?? 0);

        $accountPositions['Personnel']['now']        = ($accountPositions['Wages & Salaries']['now'] ?? 0) + ($accountPositions['Welfare Expenses']['now'] ?? 0);
        $accountPositions['Personnel']['old']        = ($accountPositions['Wages & Salaries']['old'] ?? 0) + ($accountPositions['Welfare Expenses']['old'] ?? 0);
        $accountPositions['Personnel Margin']['now'] = ($accountPositions['Personnel']['now'] ?? 0) / ($accountPositions['Sales']['now'] ?? 0);
        $accountPositions['Personnel Margin']['old'] = ($accountPositions['Personnel']['old'] ?? 0) / ($accountPositions['Sales']['old'] ?? 0);

        $accountPositions['Total Other OPEX']['now'] = ($accountPositions['Marketing']['now'] ?? 0) + ($accountPositions['Trade Fair']['now'] ?? 0) + ($accountPositions['Rental & Leasing']['now'] ?? 0) + ($accountPositions['Utilities']['now'] ?? 0) + ($accountPositions['Repair/Maintenance']['now'] ?? 0) + ($accountPositions['Carpool']['now'] ?? 0) + ($accountPositions['Stationary Expenses']['now'] ?? 0) + ($accountPositions['Communication']['now'] ?? 0) + ($accountPositions['Travel Expenses']['now'] ?? 0) + ($accountPositions['Entertainment']['now'] ?? 0) + ($accountPositions['External Consultants']['now'] ?? 0) + ($accountPositions['R&D']['now'] ?? 0) + ($accountPositions['Patents']['now'] ?? 0) + ($accountPositions['Other Personnel Expenses']['now'] ?? 0) + ($accountPositions['Other OPEX']['now'] ?? 0) + ($accountPositions['Intercompany Expenses']['now'] ?? 0) + ($accountPositions['Intercompany Revenue']['now'] ?? 0) + ($accountPositions['Doubtful Accounts']['now'] ?? 0);
        $accountPositions['Total Other OPEX']['old'] = ($accountPositions['Marketing']['old'] ?? 0) + ($accountPositions['Trade Fair']['old'] ?? 0) + ($accountPositions['Rental & Leasing']['old'] ?? 0) + ($accountPositions['Utilities']['old'] ?? 0) + ($accountPositions['Repair/Maintenance']['old'] ?? 0) + ($accountPositions['Carpool']['old'] ?? 0) + ($accountPositions['Stationary Expenses']['old'] ?? 0) + ($accountPositions['Communication']['old'] ?? 0) + ($accountPositions['Travel Expenses']['old'] ?? 0) + ($accountPositions['Entertainment']['old'] ?? 0) + ($accountPositions['External Consultants']['old'] ?? 0) + ($accountPositions['R&D']['old'] ?? 0) + ($accountPositions['Patents']['old'] ?? 0) + ($accountPositions['Other Personnel Expenses']['old'] ?? 0) + ($accountPositions['Other OPEX']['old'] ?? 0) + ($accountPositions['Intercompany Expenses']['old'] ?? 0) + ($accountPositions['Intercompany Revenue']['old'] ?? 0) + ($accountPositions['Doubtful Accounts']['old'] ?? 0);

        $accountPositions['Total Other OPEX Margin']['now'] = ($accountPositions['Total Other OPEX']['now'] ?? 0) / ($accountPositions['Sales']['now'] ?? 0);
        $accountPositions['Total Other OPEX Margin']['old'] = ($accountPositions['Total Other OPEX']['old'] ?? 0) / ($accountPositions['Sales']['old'] ?? 0);

        $accountPositions['EBITDA']['now']        = ($accountPositions['Total Other OPEX']['now'] ?? 0) + ($accountPositions['Other Revenue']['now'] ?? 0) + ($accountPositions['Gross Profit']['now'] ?? 0) + ($accountPositions['Other Selling Expenses']['now'] ?? 0) + ($accountPositions['Personnel']['now'] ?? 0);
        $accountPositions['EBITDA']['old']        = ($accountPositions['Total Other OPEX']['old'] ?? 0) + ($accountPositions['Other Revenue']['old'] ?? 0) + ($accountPositions['Gross Profit']['old'] ?? 0) + ($accountPositions['Other Selling Expenses']['old'] ?? 0) + ($accountPositions['Personnel']['old'] ?? 0);
        $accountPositions['EBITDA Margin']['now'] = ($accountPositions['EBITDA']['now'] ?? 0) / ($accountPositions['Sales']['now'] ?? 0);
        $accountPositions['EBITDA Margin']['old'] = ($accountPositions['EBITDA']['old'] ?? 0) / ($accountPositions['Sales']['old'] ?? 0);

        $accountPositions['EBIT']['now']        = ($accountPositions['EBITDA']['now'] ?? 0) + ($accountPositions['Depreciation']['now'] ?? 0);
        $accountPositions['EBIT']['old']        = ($accountPositions['EBITDA']['old'] ?? 0) + ($accountPositions['Depreciation']['old'] ?? 0);
        $accountPositions['EBIT Margin']['now'] = ($accountPositions['EBIT']['now'] ?? 0) / ($accountPositions['Sales']['now'] ?? 0);
        $accountPositions['EBIT Margin']['old'] = ($accountPositions['EBIT']['old'] ?? 0) / ($accountPositions['Sales']['old'] ?? 0);

        $accountPositions['EBT']['now']        = ($accountPositions['EBIT']['now'] ?? 0) + ($accountPositions['Interest Revenue']['now'] ?? 0) + ($accountPositions['Interest Expenses']['now'] ?? 0);
        $accountPositions['EBT']['old']        = ($accountPositions['EBIT']['old'] ?? 0) + ($accountPositions['Interest Revenue']['old'] ?? 0) + ($accountPositions['Interest Expenses']['old'] ?? 0);
        $accountPositions['EBT Margin']['now'] = ($accountPositions['EBT']['now'] ?? 0) / ($accountPositions['Sales']['now'] ?? 0);
        $accountPositions['EBT Margin']['old'] = ($accountPositions['EBT']['old'] ?? 0) / ($accountPositions['Sales']['old'] ?? 0);

        $accountPositions['Net Income']['now']        = ($accountPositions['EBT']['now'] ?? 0) + ($accountPositions['Taxes']['now'] ?? 0);
        $accountPositions['Net Income']['old']        = ($accountPositions['EBT']['old'] ?? 0) + ($accountPositions['Taxes']['old'] ?? 0);
        $accountPositions['Net Income Margin']['now'] = ($accountPositions['Net Income']['now'] ?? 0) / ($accountPositions['Sales']['now'] ?? 0);
        $accountPositions['Net Income Margin']['old'] = ($accountPositions['Net Income']['old'] ?? 0) / ($accountPositions['Sales']['old'] ?? 0);

        $view->setData('accountPositions', $accountPositions);
        $view->setData('date', $endCurrent);

        return $view;
    }

    private function loopPL(string $period, array $resultset, array &$accountPositions)
    {
        foreach ($resultset as $line) {
            $position = StructureDefinitions::getAccountPLPosition($line['Konto']);
            if (!isset($accountPositions[$position][$period])) {
                $accountPositions[$position][$period] = 0.0;
            }

            $accountPositions[$position][$period] += $line['entries'];
        }
    }

    private function calcCurrentMonth(\DateTime $date) : int
    {
        $mod = ((int) $date->format('m') - $this->app->config['fiscal_year'] - 1);
        return abs(($mod < 0 ? 12 + $mod : $mod) % 12 + 1);
    }

    private function getFiscalYearStart(SmartDateTime $date) : SmartDateTime
    {
        $newDate = new SmartDateTime($date->format('Y') . '-' . $date->format('m') . '-01');
        $newDate->smartModify(0, -$this->calcCurrentMonth($date));

        return $newDate;
    }

    private function select(string $selectQuery, \DateTime $start, \DateTime $end, string $company, array $accounts) : array
    {
        $query = new Builder($this->app->dbPool->get($company));
        $query->raw(Queries::{$selectQuery}($start, $end, $accounts));
        $result = $query->execute()->fetchAll();
        $result = empty($result) ? [] : $result;

        return $result;
    }
}