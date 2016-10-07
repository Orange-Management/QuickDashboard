<form method="GET" action="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}analysis/segmentation?{?}'); ?>">
    <table>
        <tr>
            <td><label for="segment">Segment:</label>
            <td><select id="segment" name="segment"><?php foreach(\QuickDashboard\Application\Models\StructureDefinitions::NAMING as $id => $name) : ?>
                <option value="<?= $id; ?>"<?= ((int) $this->request->getData('segment')) == (int) $id ? ' selected' : ''; ?>><?= $id; ?> - <?= $name; ?>
            <?php endforeach; ?>
            </select>
            <td style="width: 100%">
                <input type="hidden" name="u" value="<?= $this->request->getData('u') ?? ''; ?>">
                <input type="hidden" name="t" value="<?= $this->request->getData('t') ?? ''; ?>">
        <tr>
            <td colspan="3"><input type="submit" value="Analyse">
    </table>
</form>
<?php if(($this->request->getData('segment') ?? '') != '') : ?>
<?php
$sales = $this->getData('sales');
$salesAcc = $this->getData('salesAcc');
$current = $this->getData('currentFiscalYear');
$current_1 = $this->getData('currentFiscalYear')-1;
$current_2 = $this->getData('currentFiscalYear')-2;
$currentMonth = $this->getData('currentMonth');

$topCustomers = $this->getData('customer');
$customerCount = $this->getData('customerCount');
$gini = $this->getData('gini');

$salesExportDomestic = $this->getData('salesExportDomestic');
$salesDevUndev = $this->getData('salesDevUndev');
$salesRegion = $this->getData('salesRegion');
$salesCountry = $this->getData('salesCountry');
?>
<?php if(!empty($salesAcc)) : ?>
<h1><?= $id; ?> <?= $name; ?> Analysis - <?= $this->getData('date')->format('Y/m'); ?></h1>
<h2>Sales</h2>
<table>
    <thead>
    <tr>
        <th>Type
        <th>2 Years Ago
        <th>Last Year
        <th>Currently
        <th>Diff Last Year
        <th>Diff Last Year %
    <tbody>
    <tr>
        <td>Isolated Month
        <td><?= '€  ' . number_format($sales[$current_2][$currentMonth] ?? 0, 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($sales[$current_1][$currentMonth] ?? 0, 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($sales[$current][$currentMonth] ?? 0, 0, ',', '.');  ?>
        <td><?= '€  ' . number_format(($sales[$current][$currentMonth] ?? 0) - ($sales[$current_1][$currentMonth] ?? 0), 0, ',', '.');  ?>
        <td><?= !isset($sales[$current_1][$currentMonth]) || $sales[$current_1][$currentMonth] == 0 ? 0 : number_format((($sales[$current][$currentMonth] ?? 0)/$sales[$current_1][$currentMonth]-1)*100, 2, ',', '.') . '%';  ?>
    <tr>
        <td>Accumulated Year
        <td><?= '€  ' . number_format($salesAcc[$current_2][$currentMonth] ?? 0, 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($salesAcc[$current_1][$currentMonth] ?? 0, 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($salesAcc[$current][$currentMonth] ?? 0, 0, ',', '.');  ?>
        <td><?= '€  ' . number_format(($salesAcc[$current][$currentMonth] ?? 0) - ($salesAcc[$current_1][$currentMonth] ?? 0), 0, ',', '.');  ?>
        <td><?= !isset($salesAcc[$current_1][$currentMonth]) || $salesAcc[$current_1][$currentMonth] == 0? 0 : number_format((($salesAcc[$current][$currentMonth] ?? 0)/$salesAcc[$current_1][$currentMonth]-1)*100, 2, ',', '.') . '%';  ?>
</table>

<div style="width: 50%; float: left;">
    <canvas id="overview-consolidated-sales" height="270"></canvas>
</div>

<div style="width: 50%; float: left;">
    <canvas id="overview-acc-consolidated-sales" height="270"></canvas>
</div>

<div class="clear"></div>
<div class="break"></div>

<h2>Customers</h2>

<p>The customer sales distribution last year had a Gini-Coefficient of <?= number_format($gini['old'] ?? 0, 4, ',', '.'); ?> and this year of <?= number_format($gini['now'] ?? 0, 4, ',', '.'); ?>.</p>

<blockquote><i class="fa fa-2x fa-quote-left"></i>The Gini-Coefficient ranges from 0 to 1 and represents the distribution of sales, income etc. as one figure. 0 means that all customers have the same amount of sales where 1 means that all sales are generated by one customer. In this case only active customers are considered for the evaluation. The income distribution in Germany for example had a Gini-Coefficient of 0,283 in 2012.</blockquote>

<p>The following two charts show the top customers sorted in descending order based on the current sales (left chart) and the top customers sorted in descending order based on the last year sales (right chart).</p>

<?php if(!empty($topCustomers['now'])) : ?>
<div class="box" style="width: 50%; float: left">
    <canvas id="top-customers-sales" height="270"></canvas>
</div>
<?php endif; ?>

<?php if(!empty($topCustomers['old'])) : ?>
<div class="box" style="width: 50%; float: left">
    <canvas id="top-customers-sales-old" height="270"></canvas>
</div>
<?php endif; ?>

<div class="clear"></div>

<p>The follwoing chart shows the amount of active customers (have sales impact) per month.</p>

<div class="box" style="width: 100%; float: left">
    <canvas id="customers-count" height="100"></canvas>
</div>

<div class="clear"></div>
<div class="break"></div>

<h2>Location</h2>

<p>The following tables contain the sales of the current month compared to the same month of the last year. Please be aware that these figures represent the full month and not a comparison on a daily basis. The calculation of developed and undeveloped countires is based on the MANI definition. The region calculation is mostly based on the ISO-3166 definition.</p>

<table style="width: 50%; float: left;">
    <caption>Sales by Domestic/Export</caption>
    <thead>
    <tr>
        <th>Type
        <th>Last
        <th>Current
        <th>Diff
        <th>Diff %
    <tbody>
    <tr>
        <td>Export
        <td><?= number_format($salesExportDomestic['old']['Export'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesExportDomestic['now']['Export'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesExportDomestic['now']['Export'] ?? 0)-($salesExportDomestic['old']['Export'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesExportDomestic['old']['Export']) || $salesExportDomestic['old']['Export'] == 0 ? 0 : ($salesExportDomestic['now']['Export']/$salesExportDomestic['old']['Export']-1)*100, 0, ',', '.') ?> %
    <tr>
        <td>Domestic
        <td><?= number_format($salesExportDomestic['old']['Domestic'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesExportDomestic['now']['Domestic'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesExportDomestic['now']['Domestic'] ?? 0)-($salesExportDomestic['old']['Domestic'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesExportDomestic['old']['Domestic']) || $salesExportDomestic['old']['Domestic'] == 0 ? 0 : ($salesExportDomestic['now']['Domestic']/$salesExportDomestic['old']['Domestic']-1)*100, 0, ',', '.') ?> %
    <tr>
        <th>Total
        <th><?= number_format(array_sum($salesExportDomestic['old']), 0, ',', '.') ?>
        <th><?= number_format(array_sum($salesExportDomestic['now']), 0, ',', '.') ?>
        <th><?= number_format(array_sum($salesExportDomestic['now'])-array_sum($salesExportDomestic['old']), 0, ',', '.') ?>
        <th><?= number_format(!isset($salesExportDomestic['old']) || ($sum = array_sum($salesExportDomestic['old'])) == 0 ? 0 : (array_sum($salesExportDomestic['now'])/$sum-1)*100, 0, ',', '.') ?> %
</table>

<div class="box" style="width: 50%; float: left">
    <canvas id="domestic-export-chart" height="110"></canvas>
</div>

<div class="clear"></div>

<table style="width: 50%; float: left;">
    <caption>Sales by Developed/Undeveloped</caption>
    <thead>
    <tr>
        <th>Type
        <th>Last
        <th>Current
        <th>Diff
        <th>Diff %
    <tbody>
    <tr>
        <td>Developed
        <td><?= number_format($salesDevUndev['old']['Developed'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesDevUndev['now']['Developed'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesDevUndev['now']['Developed']-$salesDevUndev['old']['Developed'], 0, ',', '.') ?>
        <td><?= number_format(!isset($salesDevUndev['old']['Developed']) || $salesDevUndev['old']['Developed'] == 0 ? 0 : ($salesDevUndev['now']['Developed']/$salesDevUndev['old']['Developed']-1)*100, 0, ',', '.') ?> %
    <tr>
        <td>Undeveloped
        <td><?= number_format($salesDevUndev['old']['Undeveloped'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesDevUndev['now']['Undeveloped'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesDevUndev['now']['Undeveloped'] ?? 0)-($salesDevUndev['old']['Undeveloped'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesDevUndev['old']['Undeveloped']) || $salesDevUndev['old']['Undeveloped'] == 0 ? 0 : ($salesDevUndev['now']['Undeveloped']/$salesDevUndev['old']['Undeveloped']-1)*100, 0, ',', '.') ?> %
    <tr>
        <th>Total
        <th><?= number_format(array_sum($salesDevUndev['old']), 0, ',', '.') ?>
        <th><?= number_format(array_sum($salesDevUndev['now']), 0, ',', '.') ?>
        <th><?= number_format(array_sum($salesDevUndev['now'])-array_sum($salesDevUndev['old']), 0, ',', '.') ?>
        <th><?= number_format(!isset($salesDevUndev['old']) || ($sum = array_sum($salesDevUndev['old'])) == 0 ? 0 : (array_sum($salesDevUndev['now'])/$sum-1)*100, 0, ',', '.') ?> %
</table>

<div class="box" style="width: 50%; float: left">
    <canvas id="developed-undeveloped-chart" height="110"></canvas>
</div>

<div class="clear"></div>

<table style="width: 50%; float: left;">
    <caption>Sales by Region</caption>
    <thead>
    <tr>
        <th>Type
        <th>Last
        <th>Current
        <th>Diff
        <th>Diff %
    <tbody>
    <tr>
        <td>Europe
        <td><?= number_format($salesRegion['old']['Europe'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesRegion['now']['Europe'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesRegion['now']['Europe'] ?? 0) - ($salesRegion['old']['Europe'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesRegion['old']['Europe']) || $salesRegion['old']['Europe'] == 0 ? 0 : (($salesRegion['now']['Europe'] ?? 0)/$salesRegion['old']['Europe']-1)*100, 0, ',', '.') ?> %
    <tr>
        <td>America
        <td><?= number_format($salesRegion['old']['America'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesRegion['now']['America'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesRegion['now']['America'] ?? 0) - ($salesRegion['old']['America'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesRegion['old']['America']) || $salesRegion['old']['America'] == 0 ? 0 : (($salesRegion['now']['America'] ?? 0)/$salesRegion['old']['America']-1)*100, 0, ',', '.') ?> %
    <tr>
        <td>Asia
        <td><?= number_format($salesRegion['old']['Asia'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesRegion['now']['Asia'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesRegion['now']['Asia'] ?? 0) - ($salesRegion['old']['Asia'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesRegion['old']['Asia']) || $salesRegion['old']['Asia'] == 0 ? 0 : (($salesRegion['now']['Asia'] ?? 0)/$salesRegion['old']['Asia']-1)*100, 0, ',', '.') ?> %
    <tr>
        <td>Africa
        <td><?= number_format($salesRegion['old']['Africa'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesRegion['now']['Africa'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesRegion['now']['Africa'] ?? 0) - ($salesRegion['old']['Africa'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesRegion['old']['Africa']) || $salesRegion['old']['Africa'] == 0 ? 0 : (($salesRegion['now']['Africa'] ?? 0)/$salesRegion['old']['Africa']-1)*100, 0, ',', '.') ?> %
    <tr>
        <td>Oceania
        <td><?= number_format($salesRegion['old']['Oceania'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesRegion['now']['Oceania'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesRegion['now']['Oceania'] ?? 0) - ($salesRegion['old']['Oceania'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesRegion['old']['Oceania']) || $salesRegion['old']['Oceania'] == 0 ? 0 : (($salesRegion['now']['Oceania'] ?? 0)/$salesRegion['old']['Oceania']-1)*100, 0, ',', '.') ?> %
    <tr>
        <td>Other
        <td><?= number_format($salesRegion['old']['Other'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesRegion['now']['Other'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesRegion['now']['Other'] ?? 0) - ($salesRegion['old']['Other'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesRegion['old']['Other']) || $salesRegion['old']['Other'] == 0 ? 0 : (($salesRegion['now']['Other'] ?? 0)/$salesRegion['old']['Other']-1)*100, 0, ',', '.') ?> %
    <tr>
        <th>Total
        <th><?= number_format(array_sum($salesRegion['old']), 0, ',', '.') ?>
        <th><?= number_format(array_sum($salesRegion['now']), 0, ',', '.') ?>
        <th><?= number_format(array_sum($salesRegion['now']) - array_sum($salesRegion['old']), 0, ',', '.') ?>
        <th><?= number_format(!isset($salesRegion['old']) || $sum = array_sum($salesRegion['old']) == 0 ? 0 : (array_sum($salesRegion['now'])/$sum-1)*100, 0, ',', '.') ?> %
</table>

<div class="box" style="width: 50%; float: left">
    <canvas id="region-chart" height="200"></canvas>
</div>

<div class="clear"></div>
<div class="break"></div>

<p>The following world map shows the sales by country as well as the sales by region. A total of <?= count($salesCountry['now']); ?> countries have been delivered where last year <?= count($salesCountry['old']); ?> countries have been delivered.</p>

<div class="box" id="world-map-country" style="position: relative; width: 50%; max-height: 450px; float: left;"></div>
<div class="box" id="world-map-region" style="position: relative; width: 50%; max-height: 450px; float: left;"></div>

<div class="clear"></div>

<p>The following chart shows the current top countries as well as the sales of these countries compared to last year.</p>

<div class="box" style="width: 100%">
    <canvas id="top-countries-chart" height="100"></canvas>
</div>
<script>
    let configConsolidated = {
        type: 'line',
        data: {
            labels: ["July", "August", "September", "October", "November", "December", "January","February", "March", "April", "May", "June"],
            datasets: [{
                label: "Current Year",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $sales[$current][$i] ?? ''; } echo implode(',', $data ?? []); ?>],
                fill: false,
                borderColor: 'rgba(255,99,132,1)',
                backgroundColor: 'rgba(255,99,132,1)',
                pointBorderColor: 'rgba(255,99,132,1)',
                pointBackgroundColor: 'rgba(255,99,132,1)',
                pointBorderWidth: 0
            }, {
                label: "Last Year",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $sales[$current_1][$i] ?? ''; } echo implode(',', $data ?? []); ?>],
                fill: false,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderColor: 'rgba(54, 162, 235, 1)',
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderWidth: 0
            }, {
                label: "Two Years Ago",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $sales[$current_2][$i] ?? ''; } echo implode(',', $data ?? []); ?>],
                fill: false,
                borderColor: 'rgba(255, 206, 86, 1)',
                backgroundColor: 'rgba(255, 206, 86, 1)',
                pointBorderColor: 'rgba(255, 206, 86, 1)',
                pointBackgroundColor: 'rgba(255, 206, 86, 1)',
                pointBorderWidth: 0
            }]
        },
        options: {
            responsive: true,
            title:{
                display:true,
                text:'Consolidated Isolated Sales'
            },
            tooltips: {
                mode: 'label',
                callbacks: {
                    label: function(tooltipItem, data) {
                            let datasetLabel = data.datasets[tooltipItem.datasetIndex].label || 'Other';

                            return ' ' + datasetLabel + ': ' + '€ ' + Math.round(tooltipItem.yLabel).toString().split(/(?=(?:...)*$)/).join('.');
                          }
                }
            },
            hover: {
                mode: 'dataset'
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        show: true,
                        labelString: 'Month'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        show: true,
                        labelString: 'Sales'
                    },
                    ticks: {
                        userCallback: function(value, index, values) { return '€ ' + value.toString().split(/(?=(?:...)*$)/).join('.'); }
                    }
                }]
            }
        }
    };

    let configConsolidatedAcc = {
        type: 'line',
        data: {
            labels: ["July", "August", "September", "October", "November", "December", "January","February", "March", "April", "May", "June"],
            datasets: [{
                label: "Current Year",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $salesAcc[$current][$i] ?? ''; } echo implode(',', $data ?? []); ?>],
                fill: false,
                borderColor: 'rgba(255,99,132,1)',
                backgroundColor: 'rgba(255,99,132,1)',
                pointBorderColor: 'rgba(255,99,132,1)',
                pointBackgroundColor: 'rgba(255,99,132,1)',
                pointBorderWidth: 0
            }, {
                label: "Last Year",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $salesAcc[$current_1][$i] ?? ''; } echo implode(',', $data ?? []); ?>],
                fill: false,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderColor: 'rgba(54, 162, 235, 1)',
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderWidth: 0
            }, {
                label: "Two Years Ago",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $salesAcc[$current_2][$i] ?? ''; } echo implode(',', $data ?? []); ?>],
                fill: false,
                borderColor: 'rgba(255, 206, 86, 1)',
                backgroundColor: 'rgba(255, 206, 86, 1)',
                pointBorderColor: 'rgba(255, 206, 86, 1)',
                pointBackgroundColor: 'rgba(255, 206, 86, 1)',
                pointBorderWidth: 0
            }]
        },
        options: {
            responsive: true,
            title:{
                display:true,
                text:'Consolidated Accumulated Sales'
            },
            tooltips: {
                mode: 'label',
                callbacks: {
                    label: function(tooltipItem, data) {
                            let datasetLabel = data.datasets[tooltipItem.datasetIndex].label || 'Other';

                            return ' ' + datasetLabel + ': ' + '€ ' + Math.round(tooltipItem.yLabel).toString().split(/(?=(?:...)*$)/).join('.');
                          }
                }
            },
            hover: {
                mode: 'dataset'
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        show: true,
                        labelString: 'Month'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        show: true,
                        labelString: 'Sales'
                    },
                    ticks: {
                        userCallback: function(value, index, values) { return '€ ' + value.toString().split(/(?=(?:...)*$)/).join('.'); }
                    }
                }]
            }
        }
    };

    let configTopCustomers = {
        type: 'bar',
        data: {
            labels: [<?= !isset($topCustomers['now']) ? '' : '"' . implode('","', array_keys($top = array_slice($topCustomers['now'] ?? [], 0, 15, true))) . '"'; ?>],
            datasets: [{
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; foreach($top as $key => $value) { $data[] = (!isset($topCustomers['old'][$key]) || !is_numeric($topCustomers['old'][$key]) ? 0 : $topCustomers['old'][$key]); } echo implode(',', $data); ?>]
            }, {
                label: 'Current',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; foreach($top as $key => $value) { $data[] = (!isset($topCustomers['now'][$key]) || !is_numeric($topCustomers['now'][$key]) ? 0 : $topCustomers['now'][$key]); } echo implode(',', $data); ?>]
            }]
        },
        options: {
            responsive: true,
            hoverMode: 'label',
            hoverAnimationDuration: 400,
            stacked: false,
            title:{
                display:true,
                text:"Top Sales by Customers - Current Year"
            },
            tooltips: {
                mode: 'label',
                callbacks: {
                    label: function(tooltipItem, data) {
                        let datasetLabel = data.datasets[tooltipItem.datasetIndex].label || 'Other';

                        return ' ' + datasetLabel + ': ' + '€ ' + Math.round(tooltipItem.yLabel).toString().split(/(?=(?:...)*$)/).join('.');
                    }
                }
            },
            scales: {
                xAxes: [{
                    ticks: {
                        autoSkip: false
                    }
                }],
                yAxes: [{
                    type: "linear",
                    display: true,
                    position: "left",
                    id: "y-axis-1",
                    ticks: {
                        userCallback: function(value, index, values) { return '€ ' + value.toString().split(/(?=(?:...)*$)/).join('.'); }
                    }
                }],
            }
        }
    };

    let configTopCustomersOld = {
        type: 'bar',
        data: {
            labels: [<?= !isset($topCustomers['old']) ? '' : '"' . implode('","', array_keys($top = array_slice($topCustomers['old'], 0, 15, true))) . '"'; ?>],
            datasets: [{
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; foreach($top as $key => $value) { $data[] = (!isset($topCustomers['old'][$key]) || !is_numeric($topCustomers['old'][$key]) ? 0 : $topCustomers['old'][$key]); } echo implode(',', $data); ?>]
            }, {
                label: 'Current',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; foreach($top as $key => $value) { $data[] = (!isset($topCustomers['now'][$key]) || !is_numeric($topCustomers['now'][$key]) ? 0 : $topCustomers['now'][$key]); } echo implode(',', $data); ?>]
            }]
        },
        options: {
            responsive: true,
            hoverMode: 'label',
            hoverAnimationDuration: 400,
            stacked: false,
            title:{
                display:true,
                text:"Top Sales by Customers - Last Year"
            },
            tooltips: {
                mode: 'label',
                callbacks: {
                    label: function(tooltipItem, data) {
                        let datasetLabel = data.datasets[tooltipItem.datasetIndex].label || 'Other';

                        return ' ' + datasetLabel + ': ' + '€ ' + Math.round(tooltipItem.yLabel).toString().split(/(?=(?:...)*$)/).join('.');
                    }
                }
            },
            scales: {
                xAxes: [{
                    ticks: {
                        autoSkip: false
                    }
                }],
                yAxes: [{
                    type: "linear",
                    display: true,
                    position: "left",
                    id: "y-axis-1",
                    ticks: {
                        userCallback: function(value, index, values) { return '€ ' + value.toString().split(/(?=(?:...)*$)/).join('.'); }
                    }
                }],
            }
        }
    };


    let configCustomerCount = {
        type: 'bar',
        data: {
            labels: ["July", "August", "September", "October", "November", "December", "January","February", "March", "April", "May", "June"],
            datasets: [{
                label: 'Two Years Ago',
                backgroundColor: "rgba(255, 206, 86, 1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $customerCount[$current_2][$i] ?? 0; } echo implode(',', $data ?? []); ?>]
            }, {
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $customerCount[$current_1][$i] ?? 0; } echo implode(',', $data ?? []); ?>]
            }, {
                label: 'Current',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $customerCount[$current][$i] ?? 0; } echo implode(',', $data ?? []); ?>]
            }]
        },
        options: {
            responsive: true,
            hoverMode: 'label',
            hoverAnimationDuration: 400,
            stacked: false,
            title:{
                display:true,
                text:"Customers per Month"
            },
            tooltips: {
                mode: 'label',
                callbacks: {
                    label: function(tooltipItem, data) {
                        let datasetLabel = data.datasets[tooltipItem.datasetIndex].label || 'Other';

                        return ' ' + datasetLabel + ': ' + Math.round(tooltipItem.yLabel).toString();
                    }
                }
            },
            scales: {
                xAxes: [{
                    ticks: {
                        autoSkip: false
                    }
                }],
                yAxes: [{
                    type: "linear",
                    display: true,
                    position: "left",
                    id: "y-axis-1",
                    ticks: {
                        userCallback: function(value, index, values) { return value.toString(); },
                        beginAtZero: true,
                        min: 0
                    }
                }],
            }
        }
    };

    let configExportDomestic = {
        type: 'bar',
        data: {
            labels: ["Export", "Domestic"],
            datasets: [{
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?= $salesExportDomestic['old']['Export'] ?? 0; ?>, <?= $salesExportDomestic['old']['Domestic'] ?? 0; ?>]
            }, {
                label: 'Current',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?= $salesExportDomestic['now']['Export'] ?? 0; ?>, <?= $salesExportDomestic['now']['Domestic'] ?? 0; ?>]
            }]
        },
        options: {
            responsive: true,
            hoverMode: 'label',
            hoverAnimationDuration: 400,
            stacked: false,
            title:{
                display:false,
                text:"Export/Domestic Sales"
            },
            tooltips: {
                mode: 'label',
                callbacks: {
                    label: function(tooltipItem, data) {
                        let datasetLabel = data.datasets[tooltipItem.datasetIndex].label || 'Other';

                        return ' ' + datasetLabel + ': ' + '€ ' + Math.round(tooltipItem.yLabel).toString().split(/(?=(?:...)*$)/).join('.');
                    }
                }
            },
            scales: {
                yAxes: [{
                    type: "linear",
                    display: true,
                    position: "left",
                    id: "y-axis-1",
                    ticks: {
                        userCallback: function(value, index, values) { return '€ ' + value.toString().split(/(?=(?:...)*$)/).join('.'); }
                    }
                }],
            }
        }
    };

    let configDevelopedUndeveloped = {
        type: 'bar',
        data: {
            labels: ["Undeveloped", "Developed"],
            datasets: [{
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?= $salesDevUndev['old']['Undeveloped'] ?? 0; ?>, <?= $salesDevUndev['old']['Developed'] ?? 0; ?>]
            }, {
                label: 'Current',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?= $salesDevUndev['now']['Undeveloped'] ?? 0; ?>, <?= $salesDevUndev['now']['Developed'] ?? 0; ?>]
            }]
        },
        options: {
            responsive: true,
            hoverMode: 'label',
            hoverAnimationDuration: 400,
            stacked: false,
            title:{
                display:false,
                text:"Export/Domestic Sales"
            },
            tooltips: {
                mode: 'label',
                callbacks: {
                    label: function(tooltipItem, data) {
                        let datasetLabel = data.datasets[tooltipItem.datasetIndex].label || 'Other';

                        return ' ' + datasetLabel + ': ' + '€ ' + Math.round(tooltipItem.yLabel).toString().split(/(?=(?:...)*$)/).join('.');
                    }
                }
            },
            scales: {
                yAxes: [{
                    type: "linear",
                    display: true,
                    position: "left",
                    id: "y-axis-1",
                    ticks: {
                        userCallback: function(value, index, values) { return '€ ' + value.toString().split(/(?=(?:...)*$)/).join('.'); }
                    }
                }],
            }
        }
    };

    let configRegion = {
        type: 'bar',
        data: {
            labels: ["Other", "Oceania", "Africa", "Asia", "America", "Europe"],
            datasets: [{
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?= $salesRegion['old']['Other'] ?? 0; ?>, <?= $salesRegion['old']['Oceania'] ?? 0; ?>, <?= $salesRegion['old']['Africa'] ?? 0; ?>, <?= $salesRegion['old']['Asia'] ?? 0; ?>, <?= $salesRegion['old']['America'] ?? 0; ?>, <?= $salesRegion['old']['Europe'] ?? 0; ?>]
            }, {
                label: 'Current',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?= $salesRegion['now']['Other'] ?? 0; ?>, <?= $salesRegion['now']['Oceania'] ?? 0; ?>, <?= $salesRegion['now']['Africa'] ?? 0; ?>, <?= $salesRegion['now']['Asia'] ?? 0; ?>, <?= $salesRegion['now']['America'] ?? 0; ?>, <?= $salesRegion['now']['Europe'] ?? 0; ?>]
            }]
        },
        options: {
            responsive: true,
            hoverMode: 'label',
            hoverAnimationDuration: 400,
            stacked: false,
            title:{
                display:false,
                text:"Export/Domestic Sales"
            },
            tooltips: {
                mode: 'label',
                callbacks: {
                    label: function(tooltipItem, data) {
                        let datasetLabel = data.datasets[tooltipItem.datasetIndex].label || 'Other';

                        return ' ' + datasetLabel + ': ' + '€ ' + Math.round(tooltipItem.yLabel).toString().split(/(?=(?:...)*$)/).join('.');
                    }
                }
            },
            scales: {
                yAxes: [{
                    type: "linear",
                    display: true,
                    position: "left",
                    id: "y-axis-1",
                    ticks: {
                        userCallback: function(value, index, values) { return '€ ' + value.toString().split(/(?=(?:...)*$)/).join('.'); }
                    }
                }],
            }
        }
    };

    let worldMapCountry = new Datamap({
        scope: 'world',
        element: document.getElementById('world-map-country'),
        projection: 'mercator',
        height: 300,
        fills: {
            defaultFill: '#cccccc',
            gt50: 'red'
        },
        data: {
            <?php foreach($salesCountry['now'] as $key => $value) : if(is_string($key) && !empty($key)) : ?>
                <?= $key; ?>: {fillKey: 'gt50', value: <?= $value; ?>, country: '<?= $key; ?>'},
            <?php endif; endforeach; ?>
        },
        geographyConfig: {
            popupTemplate: function(geo, data) {
                return "<div class='hoverinfo'>Sales " + data.country + ": € " + Math.round(data.value).toString().split(/(?=(?:...)*$)/).join('.'); + "</div>";
            }
        }
    });

    let worldMapRegion = new Datamap({
        scope: 'world',
        element: document.getElementById('world-map-region'),
        projection: 'mercator',
        height: 300,
        fills: {
            defaultFill: '#cccccc',
            gt50: 'red'
        },
        data: {}
    });

    worldMapRegion.bubbles([
        {name: 'Europe', latitude: 51.1657, longitude: 10.4515, radius: <?= log($salesRegion['now']['Europe'] ?? 1); ?>, fillKey: 'gt50'},
        {name: 'Asia', latitude: 53.4815, longitude: 88.7695, radius: <?= log($salesRegion['now']['Asia'] ?? 1); ?>, fillKey: 'gt50'},
        {name: 'America', latitude: 12.8010, longitude: -87.3632, radius: <?= log($salesRegion['now']['America'] ?? 1); ?>, fillKey: 'gt50'},
        {name: 'Africa', latitude: 13.8274, longitude: 15.6445, radius: <?= log($salesRegion['now']['Africa'] ?? 1); ?>, fillKey: 'gt50'},
        {name: 'Oceania', latitude: -25.2744, longitude: 133.7751, radius: <?= log($salesRegion['now']['Oceania'] ?? 1); ?>, fillKey: 'gt50'},
    ], {
        popupTemplate: function(geo, data) {
            return "<div class='hoverinfo'>Sales " + data.name + ": € " + Math.round(Math.exp(data.radius)).toString().split(/(?=(?:...)*$)/).join('.'); + "</div>";
        }
    });

    let configTopCountries = {
        type: 'bar',
        data: {
            labels: [<?= '"' . implode('","', array_keys($top = array_slice($salesCountry['now'], 0, 15, true))) . '"'; ?>],
            datasets: [{
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; foreach($top as $key => $value) { $data[] = ($salesCountry['old'][$key] ?? 0); } echo implode(',', $data); ?>]
            }, {
                label: 'Current',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?= implode(',', $top); ?>]
            }]
        },
        options: {
            responsive: true,
            hoverMode: 'label',
            hoverAnimationDuration: 400,
            stacked: false,
            title:{
                display:true,
                text:"Top Sales by Countries"
            },
            tooltips: {
                mode: 'label',
                callbacks: {
                    label: function(tooltipItem, data) {
                        let datasetLabel = data.datasets[tooltipItem.datasetIndex].label || 'Other';

                        return ' ' + datasetLabel + ': ' + '€ ' + Math.round(tooltipItem.yLabel).toString().split(/(?=(?:...)*$)/).join('.');
                    }
                }
            },
            scales: {
                yAxes: [{
                    type: "linear",
                    display: true,
                    position: "left",
                    id: "y-axis-1",
                    ticks: {
                        userCallback: function(value, index, values) { return '€ ' + value.toString().split(/(?=(?:...)*$)/).join('.'); }
                    }
                }],
            }
        }
    };

    window.onload = function() {
        let ctxConsolidated = document.getElementById("overview-consolidated-sales").getContext("2d");
        window.consolidatedLine = new Chart(ctxConsolidated, configConsolidated);

        let ctxConsolidatedAcc = document.getElementById("overview-acc-consolidated-sales").getContext("2d");
        window.consolidatedLineAcc = new Chart(ctxConsolidatedAcc, configConsolidatedAcc);

        let ctxSalesCustomers = document.getElementById("top-customers-sales");
        window.salesCustomers = new Chart(ctxSalesCustomers, configTopCustomers);

        if(configTopCustomersOld.data.labels.length > 0) {
            let ctxSalesCustomersOld = document.getElementById("top-customers-sales-old");
            window.salesCustomersOld = new Chart(ctxSalesCustomersOld, configTopCustomersOld);
        }

        let ctxSalesCustomersCount = document.getElementById("customers-count");
        window.salesCustomersCount = new Chart(ctxSalesCustomersCount, configCustomerCount);

        let ctxExportDomestic = document.getElementById("domestic-export-chart");
        window.ExportDomestic = new Chart(ctxExportDomestic, configExportDomestic);

        let ctxDevelopedUndeveloped = document.getElementById("developed-undeveloped-chart");
        window.DevelopedUndeveloped = new Chart(ctxDevelopedUndeveloped, configDevelopedUndeveloped);

        let ctxRegion = document.getElementById("region-chart");
        window.salesRegion = new Chart(ctxRegion, configRegion);

        let ctxTopCountries = document.getElementById("top-countries-chart");
        window.salesTopCountries = new Chart(ctxTopCountries, configTopCountries);
    };
</script>
<?php endif; endif; ?>