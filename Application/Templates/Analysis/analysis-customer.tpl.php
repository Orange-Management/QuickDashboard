<form method="GET" action="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}analysis/customer'); ?>">
    <table>
        <tr>
            <td><label for="customer">Customer ID:</label>
            <td><input id="customer" name="customer" type="text" value="<?= $this->request->getData('customer') ?? ''; ?>" placeholder="123456" pattern="[0-9]{6}">
            <td style="width: 100%">
                <input type="hidden" name="u" value="<?= $this->request->getData('u') ?? ''; ?>">
                <input type="hidden" name="t" value="<?= $this->request->getData('t') ?? ''; ?>">
        <tr>
            <td colspan="5"><input type="submit" value="Analyse">
    </table>
</form>

<?php if($this->getData('customer') != null) : ?>
<?php
$customer = $this->getData('customer');
$location = $customer->getLocation();

$sales = $this->getData('sales');
$salesAcc = $this->getData('salesAcc');
$dso = $this->getData('dso');
$op = $this->getData('op');
$due = $this->getData('due');
$current = $this->getData('currentFiscalYear');
$current_1 = $this->getData('currentFiscalYear')-1;
$current_2 = $this->getData('currentFiscalYear')-2;
$currentMonth = $this->getData('currentMonth');

$gTemp = $this->getData('gTemp');
$sTemp = $this->getData('sTemp');
$tTemp = $this->getData('tTemp');

$gTemp2 = $this->getData('gTemp2');
$sTemp2 = $this->getData('sTemp2');
$tTemp2 = $this->getData('tTemp2');

$salesGroup = $this->getData('salesGroups');
$salesGroupTotal = $this->getData('salesGroupsTotal');
?>
<h1>Customer Analysis - <?= $this->getData('date')->format('Y/m'); ?></h1>

<p class="info">The following tables and chart contain the sales of the current month compared to the same month of the last year. Please be aware that these figures represent the full month and not a comparison on a daily basis.</p>

<table style="width: calc(50% - 5px); float: left;">
    <caption>Customer - <?= $customer->getName(); ?></caption>
    <thead>
    <tr>
        <th>Type
        <th>Value
    <tbody>
        <tr><th>ID:<td><?= $customer->getId(); ?>
        <tr><th>Name:<td><?= $customer->getName(); ?>
        <tr><th>Address:<td><?= $location->getAddress(); ?>
        <tr><th>Postal:<td><?= $location->getPostal(); ?> <?= $location->getCity() ?>
        <tr><th>Country:<td><?= $location->getCountry(); ?>
        <tr><th>Group:<td><?= $customer->getCustomerGroup(); ?>
        <tr><th>Sales Rep:<td><?= $customer->getSalesRep(); ?>
        <tr><th>Registered:<td><?= $customer->getCreatedAt()->format('Y-m-d'); ?>
</table>

<?php if(isset($sales[$current]) || isset($sales[$current_1]) || isset($sales[$current_2])) : ?>
<table style="width: calc(50% - 5px); float: right;">
    <caption>Stats</caption>
    <thead>
    <tr>
        <th>Type
        <th>Last Year
        <th>Current
        <th>Diff
    <tbody>
        <tr><th>Sales:<td><?= number_format($old = ($salesAcc[$current_1][$currentMonth] ?? 0), 0, ',', '.'); ?><td><?= number_format($now = ($salesAcc[$current][$currentMonth] ?? 0), 0, ',', '.'); ?><td><?= $old == 0 ? 0 : number_format(($now/$old - 1)*100, 0, ',', '.'); ?> %
        <tr><th>Total Sales:<td><?= number_format($old = ($salesAcc[$current_1][12] ?? 0), 0, ',', '.'); ?><td><?= number_format($now = ($salesAcc[$current][$currentMonth] ?? 0), 0, ',', '.'); ?><td><?= $old == 0 ? 0 : number_format(($now/$old - 1)*100, 0, ',', '.'); ?> %
        <tr><th>DSO:<td><?= $dso['old']; ?><td><?= $dso['now']; ?><td><?= (!isset($dso['old']) || $dso['old'] == 0) ? 0 : number_format(($dso['now']/$dso['old'] - 1)*100, 0, ',', '.'); ?> %
        <tr><th>Balance:<td><?= number_format($old = ($op['old'] ?? 0), 0, ',', '.'); ?><td><?= number_format($now = ($op['now'] ?? 0), 0, ',', '.'); ?><td><?= $old == 0 ? 0 : number_format(($now/$old - 1)*100, 0, ',', '.'); ?> %
        <tr><th>Due:<td><?= number_format($old = ($due['old'] ?? 0), 0, ',', '.'); ?><td><?= number_format($now = ($due['now'] ?? 0), 0, ',', '.'); ?><td><?= $old == 0 ? 0 : number_format(($now/$old - 1)*100, 0, ',', '.'); ?> %
</table>

<div class="clear"></div>

<div class="box" style="width: 50%; float: left">
    <canvas id="sales-month" height="270"></canvas>
</div>

<div class="box" style="width: 50%; float: left">
    <canvas id="sales-month-acc" height="270"></canvas>
</div>

<div class="clear"></div>

<div class="box" style="width: 100%; float: left">
    <canvas id="sales-groups" height="130"></canvas>
</div>

<table style="width: 100%; float: left;">
    <caption>Sales Segmentation</caption>
    <thead>
    <tr>
        <th>Segment
        <th>Group
        <th>Last
        <th>Current
        <th>Diff
        <th>Diff %
    <tbody>
    <?php foreach($gTemp as $segment => $groups) : if(!is_array($groups)) { continue; } foreach($groups as $group => $temp) : ?>
    <tr>
        <td><?= $segment; ?>
        <td><?= $group; ?>
        <td><?= number_format($gTemp[$segment][$group][$current_1] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($gTemp[$segment][$group][$current] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($gTemp[$segment][$group][$current] ?? 0)-($gTemp[$segment][$group][$current_1] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($gTemp[$segment][$group][$current_1]) || $gTemp[$segment][$group][$current_1] == 0 ? 0 : (($gTemp[$segment][$group][$current] ?? 0)/$gTemp[$segment][$group][$current_1]-1)*100, 0, ',', '.') ?> %
    <?php endforeach; ?>
    <tr>
        <th><?= $segment; ?>
        <th>Total
        <th><?= number_format($sTemp[$segment][$current_1] ?? 0, 0, ',', '.') ?>
        <th><?= number_format($sTemp[$segment][$current] ?? 0, 0, ',', '.') ?>
        <th><?= number_format(($sTemp[$segment][$current] ?? 0)-($sTemp[$segment][$current_1] ?? 0), 0, ',', '.') ?>
        <th><?= number_format(!isset($sTemp[$segment][$current_1]) || $sTemp[$segment][$current_1] == 0 ? 0 : (($sTemp[$segment][$current] ?? 0)/$sTemp[$segment][$current_1]-1)*100, 0, ',', '.') ?> %
    <?php endforeach; ?>
    <tr>
        <th colspan="2">Total
        <th><?= number_format($tTemp[$current_1], 0, ',', '.') ?>
        <th><?= number_format($tTemp[$current], 0, ',', '.') ?>
        <th><?= number_format($tTemp[$current]-$tTemp[$current_1], 0, ',', '.') ?>
        <th><?= number_format(!is_numeric($tTemp[$current_1]) || $tTemp[$current_1] == 0 ? 0 : (($tTemp[$current] ?? 0)/$tTemp[$current_1]-1)*100, 0, ',', '.') ?> %
</table>

<div class="clear"></div>

<div class="box" style="width: 100%; float: left">
    <canvas id="sales-groups-total" height="130"></canvas>
</div>

<table style="width: 100%; float: left;">
    <caption>Sales Segmentation full FY</caption>
    <thead>
    <tr>
        <th>Segment
        <th>Group
        <th>Last
        <th>Current
        <th>Diff
        <th>Diff %
    <tbody>
    <?php foreach($gTemp2 as $segment => $groups) : if(!is_array($groups)) { continue; } foreach($groups as $group => $temp2) : ?>
    <tr>
        <td><?= $segment; ?>
        <td><?= $group; ?>
        <td><?= number_format($gTemp2[$segment][$group][$current_1] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($gTemp2[$segment][$group][$current] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($gTemp2[$segment][$group][$current] ?? 0)-($gTemp2[$segment][$group][$current_1] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($gTemp2[$segment][$group][$current_1]) || $gTemp2[$segment][$group][$current_1] == 0 ? 0 : (($gTemp2[$segment][$group][$current] ?? 0)/$gTemp2[$segment][$group][$current_1]-1)*100, 0, ',', '.') ?> %
    <?php endforeach; ?>
    <tr>
        <th><?= $segment; ?>
        <th>Total
        <th><?= number_format($sTemp2[$segment][$current_1] ?? 0, 0, ',', '.') ?>
        <th><?= number_format($sTemp2[$segment][$current] ?? 0, 0, ',', '.') ?>
        <th><?= number_format(($sTemp2[$segment][$current] ?? 0)-($sTemp2[$segment][$current_1] ?? 0), 0, ',', '.') ?>
        <th><?= number_format(!isset($sTemp2[$segment][$current_1]) || $sTemp2[$segment][$current_1] == 0 ? 0 : (($sTemp2[$segment][$current] ?? 0)/$sTemp2[$segment][$current_1]-1)*100, 0, ',', '.') ?> %
    <?php endforeach; ?>
    <tr>
        <th colspan="2">Total
        <th><?= number_format($tTemp2[$current_1], 0, ',', '.') ?>
        <th><?= number_format($tTemp2[$current], 0, ',', '.') ?>
        <th><?= number_format($tTemp2[$current]-$tTemp2[$current_1], 0, ',', '.') ?>
        <th><?= number_format(!is_numeric($tTemp2[$current_1]) || $tTemp2[$current_1] == 0 ? 0 : (($tTemp2[$current] ?? 0)/$tTemp2[$current_1]-1)*100, 0, ',', '.') ?> %
</table>

<div class="clear"></div>

<script>
    let configConsolidated = {
        type: 'line',
        data: {
            labels: ["July", "August", "September", "October", "November", "December", "January","February", "March", "April", "May", "June"],
            datasets: [{
                label: "Current Year",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = ($i <= $currentMonth) ? $sales[$current][$i] ?? '0' : ''; } echo implode(',', $data ?? []); ?>],
                fill: false,
                borderColor: 'rgba(255,99,132,1)',
                backgroundColor: 'rgba(255,99,132,1)',
                pointBorderColor: 'rgba(255,99,132,1)',
                pointBackgroundColor: 'rgba(255,99,132,1)',
                pointBorderWidth: 0,
                cubicInterpolationMode: 'monotone'
            }, {
                label: "Last Year",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $sales[$current_1][$i] ?? '0'; } echo implode(',', $data ?? []); ?>],
                fill: false,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderColor: 'rgba(54, 162, 235, 1)',
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderWidth: 0,
                cubicInterpolationMode: 'monotone'
            }, {
                label: "Two Years Ago",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $sales[$current_2][$i] ?? '0'; } echo implode(',', $data ?? []); ?>],
                fill: false,
                borderColor: 'rgba(255, 206, 86, 1)',
                backgroundColor: 'rgba(255, 206, 86, 1)',
                pointBorderColor: 'rgba(255, 206, 86, 1)',
                pointBackgroundColor: 'rgba(255, 206, 86, 1)',
                pointBorderWidth: 0,
                cubicInterpolationMode: 'monotone'
            }]
        },
        options: {
            responsive: true,
            title:{
                display:true,
                text:'Isolated Sales'
            },
            tooltips: {
                mode: 'label',
                callbacks: {
                    label: function(tooltipItem, data) {
                            let datasetLabel = data.datasets[tooltipItem.datasetIndex].label || 'Other';

                            return ' ' + datasetLabel + ': ' + '€ ' + Math.round(tooltipItem.yLabel).toString().split(/(?=(?:...)*$)/).join('.').replace('-.', '-');
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
                        userCallback: function(value, index, values) { return '€ ' + value.toString().split(/(?=(?:...)*$)/).join('.').replace('-.', '-'); }
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
                pointBorderWidth: 0,
                cubicInterpolationMode: 'monotone'
            }, {
                label: "Last Year",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $salesAcc[$current_1][$i] ?? ''; } echo implode(',', $data ?? []); ?>],
                fill: false,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderColor: 'rgba(54, 162, 235, 1)',
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderWidth: 0,
                cubicInterpolationMode: 'monotone'
            }, {
                label: "Two Years Ago",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $salesAcc[$current_2][$i] ?? ''; } echo implode(',', $data ?? []); ?>],
                fill: false,
                borderColor: 'rgba(255, 206, 86, 1)',
                backgroundColor: 'rgba(255, 206, 86, 1)',
                pointBorderColor: 'rgba(255, 206, 86, 1)',
                pointBackgroundColor: 'rgba(255, 206, 86, 1)',
                pointBorderWidth: 0,
                cubicInterpolationMode: 'monotone'
            }]
        },
        options: {
            responsive: true,
            title:{
                display:true,
                text:'Accumulated Sales'
            },
            tooltips: {
                mode: 'label',
                callbacks: {
                    label: function(tooltipItem, data) {
                            let datasetLabel = data.datasets[tooltipItem.datasetIndex].label || 'Other';

                            return ' ' + datasetLabel + ': ' + '€ ' + Math.round(tooltipItem.yLabel).toString().split(/(?=(?:...)*$)/).join('.').replace('-.', '-');
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
                        userCallback: function(value, index, values) { return '€ ' + value.toString().split(/(?=(?:...)*$)/).join('.').replace('-.', '-'); }
                    }
                }]
            }
        }
    };

    let configSalesGroups = {
        type: 'bar',
        data: {
            labels: [<?php $groupNames = array_unique(array_merge(array_keys($salesGroup[$current] ?? []), array_keys($salesGroup[$current_1] ?? []), array_keys($salesGroup[$current_2] ?? []))); echo '"' . implode('","', $groupNames) . '"'; ?>],
            datasets: [{
                label: 'Two Years Ago',
                backgroundColor: "rgba(255, 206, 86, 1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; foreach($groupNames as $key => $name) { $data[] = $salesGroup[$current_2][$name] ?? 0; } echo implode(',', $data); ?>]
            }, {
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; foreach($groupNames as $key => $name) { $data[] = $salesGroup[$current_1][$name] ?? 0; } echo implode(',', $data); ?>]
            }, {
                label: 'Current',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; foreach($groupNames as $key => $name) { $data[] = $salesGroup[$current][$name] ?? 0; } echo implode(',', $data); ?>]
            }]
        },
        options: {
            responsive: true,
            hoverMode: 'label',
            hoverAnimationDuration: 400,
            stacked: false,
            title:{
                display:true,
                text:"Sales by Groups"
            },
            tooltips: {
                mode: 'label',
                callbacks: {
                    label: function(tooltipItem, data) {
                        let datasetLabel = data.datasets[tooltipItem.datasetIndex].label || 'Other';

                        return ' ' + datasetLabel + ': ' + '€ ' + Math.round(tooltipItem.yLabel).toString().split(/(?=(?:...)*$)/).join('.').replace('-.', '-');
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
                        userCallback: function(value, index, values) { return '€ ' + value.toString().split(/(?=(?:...)*$)/).join('.').replace('-.', '-'); },
                        beginAtZero: true,
                        min: 0
                    }
                }],
            }
        }
    };

    let configSalesGroupsTotal = {
        type: 'bar',
        data: {
            labels: [<?php $groupNames = array_unique(array_merge(array_keys($salesGroupTotal[$current] ?? []), array_keys($salesGroupTotal[$current_1] ?? []), array_keys($salesGroupTotal[$current_2] ?? []))); echo '"' . implode('","', $groupNames) . '"'; ?>],
            datasets: [{
                label: 'Two Years Ago',
                backgroundColor: "rgba(255, 206, 86, 1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; foreach($groupNames as $key => $name) { $data[] = $salesGroupTotal[$current_2][$name] ?? 0; } echo implode(',', $data); ?>]
            }, {
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; foreach($groupNames as $key => $name) { $data[] = $salesGroupTotal[$current_1][$name] ?? 0; } echo implode(',', $data); ?>]
            }, {
                label: 'Current',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; foreach($groupNames as $key => $name) { $data[] = $salesGroupTotal[$current][$name] ?? 0; } echo implode(',', $data); ?>]
            }]
        },
        options: {
            responsive: true,
            hoverMode: 'label',
            hoverAnimationDuration: 400,
            stacked: false,
            title:{
                display:true,
                text:"Total Sales by Groups"
            },
            tooltips: {
                mode: 'label',
                callbacks: {
                    label: function(tooltipItem, data) {
                        let datasetLabel = data.datasets[tooltipItem.datasetIndex].label || 'Other';

                        return ' ' + datasetLabel + ': ' + '€ ' + Math.round(tooltipItem.yLabel).toString().split(/(?=(?:...)*$)/).join('.').replace('-.', '-');
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
                        userCallback: function(value, index, values) { return '€ ' + value.toString().split(/(?=(?:...)*$)/).join('.').replace('-.', '-'); },
                        beginAtZero: true,
                        min: 0
                    }
                }],
            }
        }
    };

    window.onload = function() {
        let ctxConsolidated = document.getElementById("sales-month").getContext("2d");
        window.consolidatedLine = new Chart(ctxConsolidated, configConsolidated);

        let ctxConsolidatedAcc = document.getElementById("sales-month-acc").getContext("2d");
        window.consolidatedLineAcc = new Chart(ctxConsolidatedAcc, configConsolidatedAcc);

        let ctxGroupSales = document.getElementById("sales-groups").getContext("2d");
        window.groupSales = new Chart(ctxGroupSales, configSalesGroups);

        let ctxGroupSalesTotal = document.getElementById("sales-groups-total").getContext("2d");
        window.groupSalesTotal = new Chart(ctxGroupSalesTotal, configSalesGroupsTotal);
    };
</script>
<?php endif; endif; ?>