<?php
$salesGroups = $this->getData('salesGroups');
$totalGroups = $this->getData('totalGroups');
$topCustomers = $this->getData('customer');
$customerCount = $this->getData('customerCount');
$gini = $this->getData('gini');
$current = $this->getData('currentFiscalYear');
$current_1 = $this->getData('currentFiscalYear')-1;
$current_2 = $this->getData('currentFiscalYear')-2;
$monthlyNewCustomer = $this->getData('monthlyNewCustomer');
?>
<h1>Sales Customers - <?= $this->getData('date')->format('Y/m'); ?> <?= $this->getData('type'); ?></h1>
<p class="info">The following tables contain the sales of the current month compared to the same month of the last year. Please be aware that these figures represent the full month and not a comparison on a daily basis.</p>

<table style="width: 50%; float: left;">
    <caption>Sales by Customer Groups</caption>
    <thead>
    <tr>
        <th>Group
        <th>Last
        <th>Current
        <th>Diff
        <th>Diff %
    <tbody>
    <?php foreach($salesGroups as $group => $groups) : ?>
    <tr>
        <td><?= $group; ?>
        <td><?= number_format($salesGroups[$group]['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesGroups[$group]['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesGroups[$group]['now'] ?? 0)-($salesGroups[$group]['old'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesGroups[$group]) || $salesGroups[$group]['old'] == 0 ? 0 : (($salesGroups[$group]['now'] ?? 0)/$salesGroups[$group]['old']-1)*100, 0, ',', '.') ?> %
    <?php endforeach; ?>
    <!--<tr>
        <th>Total
        <th><?= number_format($totalGroups['old'] ?? 0, 0, ',', '.') ?>
        <th><?= number_format($totalGroups['now'] ?? 0, 0, ',', '.') ?>
        <th><?= number_format(($totalGroups['now'] ?? 0)-($totalGroups['old'] ?? 0), 0, ',', '.') ?>
        <th><?= number_format(!isset($totalGroups['old']) ? 0 : (($totalGroups['now'] ?? 0)/$totalGroups['old']-1)*100, 0, ',', '.') ?> %-->
</table>

<div class="box" style="width: 50%; float: left">
    <canvas id="group-sales" height="<?= (int) (23.3 * count($salesGroups) + 30); ?>"></canvas>
</div>

<div class="clear"></div>

<p>The customer sales distribution last year had a Gini-Coefficient of <?= number_format($gini['old'], 4, ',', '.'); ?> and this year of <?= number_format($gini['now'], 4, ',', '.'); ?>.</p>

<blockquote><i class="fa fa-2x fa-quote-left"></i>The Gini-Coefficient ranges from 0 to 1 and represents the distribution of sales, income etc. as one figure. 0 means that all customers have the same amount of sales where 1 means that all sales are generated by one customer. In this case only active customers are considered for the evaluation. The income distribution in Germany for example had a Gini-Coefficient of 0,283 in 2012.</blockquote>

<p>The following two charts show the top customers sorted in descending order based on the current sales (left chart) and the top customers sorted in descending order based on the last year sales (right chart).</p>

<div class="box" style="width: 50%; float: left">
    <canvas id="top-customers-sales" height="270"></canvas>
</div>

<div class="box" style="width: 50%; float: left">
    <canvas id="top-customers-sales-old" height="270"></canvas>
</div>

<div class="clear"></div>
<div class="break"></div>

<p>There are <?= $this->getData('newCustomers'); ?> new customers during the last 12 month and <?= $this->getData('lostCustomers'); ?> customers are lost compared to the previous 12 month.</p>

<p>The follwoing chart shows the amount of active customers (have sales impact) per month.</p>

<div class="box" style="width: 100%; float: left">
    <canvas id="customers-count" height="100"></canvas>
</div>

<p>The follwoing chart shows the amount of new customers per month.</p>

<div class="box" style="width: 100%; float: left">
    <canvas id="new-customers-count" height="100"></canvas>
</div>

<div class="clear"></div>

<script>
    let configSalesGroups = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [
                    <?php $data = ''; foreach($salesGroups as $group) { $data .= number_format(($group['now'] ?? 0) / $totalGroups['now'] * 100, 0, ',', '.') . ','; } echo rtrim($data, ','); ?>
                ],
                backgroundColor: [
                    "#56E2CF",
                    "#5668E2",
                    "#CF56E2",
                    "#E25668",
                    "#E2CF56",
                    "#68E256",
                    "#56AEE2",
                    "#8A56E2",
                    "#E256AE",
                    "#E28956",
                    "#AEE256",
                    "#56E289"
                ],
                label: 'Current'
            }, {
                data: [
                    <?php $data = ''; foreach($salesGroups as $group) { $data .= number_format(($group['old'] ?? 0) / $totalGroups['old'] * 100, 0, ',', '.') . ','; } echo rtrim($data, ','); ?>
                ],
                backgroundColor: [
                    "#56E2CF",
                    "#5668E2",
                    "#CF56E2",
                    "#E25668",
                    "#E2CF56",
                    "#68E256",
                    "#56AEE2",
                    "#8A56E2",
                    "#E256AE",
                    "#E28956",
                    "#AEE256",
                    "#56E289"
                ],
                label: 'Last Year'
            }],
            labels: [
                <?= '"' . implode('","', array_keys($salesGroups)) . '"'; ?>
            ]
        },
        options: {
            responsive: true,
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Sales Ratio by Groups'
            },
            animation: {
                animateScale: true,
                animateRotate: true
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                            let datasetLabel = data.labels[tooltipItem.index] || 'Other';

                            return data.datasets[tooltipItem.datasetIndex].label + ' - ' + datasetLabel + ': ' + Math.round(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]).toString().split(/(?=(?:...)*$)/).join('.') + '%';
                          }
                }
            },
        }
    };

    let configTopCustomers = {
        type: 'bar',
        data: {
            labels: [<?= '"' . implode('","', array_keys($top = array_slice($topCustomers['now'], 0, 15, true))) . '"'; ?>],
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
            labels: [<?= '"' . implode('","', array_keys($top = array_slice($topCustomers['old'], 0, 15, true))) . '"'; ?>],
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
                data: [<?php echo implode(',', $customerCount[$current_2]); ?>]
            }, {
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?php echo implode(',', $customerCount[$current_1]); ?>]
            }, {
                label: 'Current',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?php echo implode(',', $customerCount[$current]); ?>]
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
        let ctxSalesGroups = document.getElementById("group-sales");
        window.salesGroups = new Chart(ctxSalesGroups, configSalesGroups);

        let ctxSalesCustomers = document.getElementById("top-customers-sales");
        window.salesCustomers = new Chart(ctxSalesCustomers, configTopCustomers);

        let ctxSalesCustomersOld = document.getElementById("top-customers-sales-old");
        window.salesCustomersOld = new Chart(ctxSalesCustomersOld, configTopCustomersOld);

        let ctxSalesCustomersCount = document.getElementById("customers-count");
        window.salesCustomersCount = new Chart(ctxSalesCustomersCount, configCustomerCount);
    
        let ctxSalesNewCustomersCount = document.getElementById("new-customers-count");
        window.salesNewCustomersCount = new Chart(ctxSalesNewCustomersCount, configNewCustomerCount);
    };
</script>