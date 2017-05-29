<form method="GET" action="<?= \phpOMS\Uri\UriFactory::build('{/base}/analysis/sales?{?}'); ?>">
    <table>
        <tr>
            <td><label for="segment">Segment:</label>
            <td><select id="segment" name="segment">
            <option value="All"<?= $this->request->getData('segment') == 'All' ? ' selected' : ''; ?>>All
            <?php foreach(\QuickDashboard\Application\Models\StructureDefinitions::NAMING as $id => $name) : ?>
                <option value="<?= $id; ?>"<?php if(((int) $this->request->getData('segment')) == (int) $id) { echo ' selected'; $gId = $id; $gName = $name; }; ?>><?= $id; ?> - <?= $name; ?>
            <?php endforeach; ?>
            </select>
        <tr>
            <td><label for="location">Location:</label>
            <td><select id="location" name="location">
                <option value="All"<?= $this->request->getData('location') == 'All' ? ' selected' : ''; ?>>All
                <optgroup label="Domestic/Export">
                    <option value="Domestic"<?= $this->request->getData('location') == 'Domestic' ? ' selected' : ''; ?>>Domestic
                    <option value="Export"<?= $this->request->getData('location') == 'Export' ? ' selected' : ''; ?>>Export
                <optgroup label="Country">
            <?php $countries = \QuickDashboard\Application\Models\StructureDefinitions::getCountries(); asort($countries); foreach($countries as $id => $name) : ?>
                <option value="<?= $name; ?>"<?= $this->request->getData('location') == $name ? ' selected' : ''; ?>><?= $name; ?>
            <?php endforeach; ?>
            </select>
        <tr>
            <td><label for="rep">Employees:</label>
            <td><select id="rep" name="rep">
                <option value="All"<?= $this->request->getData('rep') == 'All' ? ' selected' : ''; ?>>All
            <?php $repNames = $this->getData('repNames'); foreach($repNames as $id => $name) : if(preg_match('/^[0-9]*$/', trim($id)) === 1) : ?>
                <option value="<?= trim($id); ?>"<?= $this->request->getData('rep') == trim($id) ? ' selected' : ''; ?>><?= trim($name); ?>
            <?php endif; endforeach; ?>
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

$salesGroups = $this->getData('salesGroups');
$segmentGroups = $this->getData('segmentGroups');
$totalGroups = $this->getData('totalGroups');

$topCustomers = $this->getData('customer');
$customerCount = $this->getData('customerCount');
$monthlyNewCustomer = $this->getData('monthlyNewCustomer');
$gini = $this->getData('gini');

$reps = $this->getData('repsSales');
?>
<?php if(!empty($salesAcc)) : ?>
<h1>S: <?= $this->request->getData('segment') ?? 'All'; ?> / L: <?= $this->request->getData('location') ?? 'All'; ?> / R: <?= $this->request->getData('rep') ?? 'All'; ?> Analysis - <?= $this->getData('date')->format('Y/m'); ?></h1>
<h2>Sales</h2>
<?php include __DIR__ . '/../Sales/table-overview.tpl.php'; ?>

<div style="width: 50%; float: left;">
    <canvas id="overview-consolidated-sales" height="270"></canvas>
</div>

<div style="width: 50%; float: left;">
    <canvas id="overview-acc-consolidated-sales" height="270"></canvas>
</div>

<div class="box" style="width: 100%; float: left">
    <canvas id="group-sales" height="150"></canvas>
</div>

<div class="clear"></div>
<div class="break"></div>

<?php include __DIR__ . '/../Sales/table-segment.tpl.php'; ?>

<div class="clear"></div>
<div class="break"></div>

<h2>Customers</h2>

<p>The customer sales distribution last year had a Gini-Coefficient of <?= number_format($gini['old'] ?? 0, 4, ',', '.'); ?> and this year of <?= number_format($gini['now'] ?? 0, 4, ',', '.'); ?>.</p>

<blockquote><i class="fa fa-2x fa-quote-left"></i>The Gini-Coefficient ranges from 0 to 1 and represents the distribution of sales, income etc. as one figure. 0 means that all customers have the same amount of sales where 1 means that all sales are generated by one customer. In this case only active customers are considered for the evaluation. The income distribution in Germany for example had a Gini-Coefficient of 0,283 in 2012.</blockquote>

<p>There are <?= $this->getData('newCustomers'); ?> new customers during the last 12 month and <?= $this->getData('lostCustomers'); ?> customers are lost compared to the previous 12 month.</p>

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
<div class="break"></div>

<p>The follwoing chart shows the amount of active customers (have sales impact) per month.</p>

<div class="box" style="width: 100%; float: left">
    <canvas id="customers-count" height="100"></canvas>
</div>

<p>The follwoing chart shows the amount of new customers per month.</p>

<div class="box" style="width: 100%; float: left">
    <canvas id="new-customers-count" height="100"></canvas>
</div>

<div class="clear"></div>
<div class="break"></div>

<h2>Sales Reps</h2>
<p>The sales by sales reps are based on all customers assigned to that sales rep. This also includes sales that are usually not recognized in other reportings as part of the sales rep sales. The total sales by sales rep can be different from the actual total sales due to cut-off tests and the resulting different sales recognition in the correct period.</p>
<table>
    <caption>Sales by Sales Rep</caption>
    <thead>
        <tr>
            <th>Name
            <th>Prev. Year
            <th>Current
            <th>Diff.
            <th>Diff. %
    <tbody>
        <?php foreach($reps as $name => $value) : ?>
        <tr>
            <td><?= $name; ?>
            <td><?= number_format($reps[$name]['old'] ?? 0, 0, ',', '.') ?>
            <td><?= number_format($reps[$name]['now'] ?? 0, 0, ',', '.') ?>
            <td><?= number_format(($reps[$name]['now'] ?? 0)-($reps[$name]['old'] ?? 0), 0, ',', '.') ?>
            <td><?= number_format(!isset($reps[$name]['old']) || $reps[$name]['old'] == 0 ? 0 : (($reps[$name]['now'] ?? 0)/$reps[$name]['old']-1)*100, 0, ',', '.') ?> %
    <?php endforeach; ?>
</table>
<script>
    let configConsolidated = {
        type: 'line',
        data: {
            labels: ["July", "August", "September", "October", "November", "December", "January","February", "March", "April", "May", "June"],
            datasets: [{
                label: "Current Year",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $sales[$current][$i] ?? 0; } echo implode(',', $data ?? []); ?>],
                fill: false,
                borderColor: 'rgba(255,99,132,1)',
                backgroundColor: 'rgba(255,99,132,1)',
                pointBorderColor: 'rgba(255,99,132,1)',
                pointBackgroundColor: 'rgba(255,99,132,1)',
                pointBorderWidth: 0,
                cubicInterpolationMode: 'monotone'
            }, {
                label: "Last Year",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $sales[$current_1][$i] ?? 0; } echo implode(',', $data ?? []); ?>],
                fill: false,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderColor: 'rgba(54, 162, 235, 1)',
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderWidth: 0,
                cubicInterpolationMode: 'monotone'
            }, {
                label: "Two Years Ago",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $sales[$current_2][$i] ?? 0; } echo implode(',', $data ?? []); ?>],
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
                text:'Consolidated Isolated Sales'
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
                text:'Consolidated Accumulated Sales'
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
            labels: [<?php $groupNames = []; foreach($salesGroups['All'] as $key => $groups) { if(!is_array($groups)) { continue; } $groupNames = array_merge($groupNames, array_keys($groups)); }; echo '"' . implode('","', $groupNames) . '"'; ?>],
            datasets: [{
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = ''; foreach($salesGroups['All'] as $key => $groups) { if(!is_array($groups)) { continue; } foreach($groups as $group) { $data .= ($group['old'] ?? 0) . ','; } } echo rtrim($data, ','); ?>]
            }, {
                label: 'Current',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = ''; foreach($salesGroups['All'] as $key => $groups) { if(!is_array($groups)) { continue; } foreach($groups as $group) { $data .= ($group['now']  ?? 0) . ','; } } echo rtrim($data, ','); ?>]
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
                        userCallback: function(value, index, values) { return '€ ' + value.toString().split(/(?=(?:...)*$)/).join('.').replace('-.', '-'); }
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
                        userCallback: function(value, index, values) { return '€ ' + value.toString().split(/(?=(?:...)*$)/).join('.').replace('-.', '-'); }
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

    let configNewCustomerCount = {
        type: 'bar',
        data: {
            labels: ["July", "August", "September", "October", "November", "December", "January","February", "March", "April", "May", "June"],
            datasets: [{
                label: 'Two Years Ago',
                backgroundColor: "rgba(255, 206, 86, 1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $monthlyNewCustomer[$current_2][$i] ?? 0; } echo implode(',', $data ?? []); ?>]
            }, {
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $monthlyNewCustomer[$current_1][$i] ?? 0; } echo implode(',', $data ?? []); ?>]
            }, {
                label: 'Current',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $monthlyNewCustomer[$current][$i] ?? 0; } echo implode(',', $data ?? []); ?>]
            }]
        },
        options: {
            responsive: true,
            hoverMode: 'label',
            hoverAnimationDuration: 400,
            stacked: false,
            title:{
                display:true,
                text:"New Customers per Month"
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

    window.onload = function() {
        let ctxConsolidated = document.getElementById("overview-consolidated-sales").getContext("2d");
        window.consolidatedLine = new Chart(ctxConsolidated, configConsolidated);

        let ctxConsolidatedAcc = document.getElementById("overview-acc-consolidated-sales").getContext("2d");
        window.consolidatedLineAcc = new Chart(ctxConsolidatedAcc, configConsolidatedAcc);

        let ctxSalesCustomers = document.getElementById("top-customers-sales");
        window.salesCustomers = new Chart(ctxSalesCustomers, configTopCustomers);

        let ctxSalesGroups = document.getElementById("group-sales");
        window.salesGroups = new Chart(ctxSalesGroups, configSalesGroups);

        if(configTopCustomersOld.data.labels.length > 0) {
            let ctxSalesCustomersOld = document.getElementById("top-customers-sales-old");
            window.salesCustomersOld = new Chart(ctxSalesCustomersOld, configTopCustomersOld);
        }

        let ctxSalesCustomersCount = document.getElementById("customers-count");
        window.salesCustomersCount = new Chart(ctxSalesCustomersCount, configCustomerCount);

        let ctxSalesNewCustomersCount = document.getElementById("new-customers-count");
        window.salesNewCustomersCount = new Chart(ctxSalesNewCustomersCount, configNewCustomerCount);
    };
</script>
<?php endif; endif; ?>