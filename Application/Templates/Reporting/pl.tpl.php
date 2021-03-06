<?php
$pl = $this->getData('accountPositions');
$diffs = [];
?>
<h1>P&L - <?= $this->getData('date')->format('Y/m'); ?> <?= $this->getData('type'); ?></h1>

<table style="width: 100%; float: left;">
    <caption>P&L</caption>
    <thead>
    <tr>
        <th>Name
        <th>Last
        <th>Current
        <th>Diff
        <th>Diff %
    <tbody>
    <tr>
        <td>Sales
        <td><?= number_format($pl['Sales']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Sales']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['Sales']['now'] ?? 0) - ($pl['Sales']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Sales']['old']) ? 0 : number_format((($pl['Sales']['now'] ?? 0)/$pl['Sales']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>COGS Material
        <td><?= number_format($pl['COGS Material']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['COGS Material']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['COGS Material']['now'] ?? 0) - ($pl['COGS Material']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['COGS Material']['old']) ? 0 : number_format((($pl['COGS Material']['now'] ?? 0)/$pl['COGS Material']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>COGS Services
        <td><?= number_format($pl['COGS Services']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['COGS Services']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['COGS Services']['now'] ?? 0) - ($pl['COGS Services']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['COGS Services']['old']) ? 0 : number_format((($pl['COGS Services']['now'] ?? 0)/$pl['COGS Services']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <th>Gross Profit
        <th><?= number_format($pl['Gross Profit']['old'] ?? 0, 0, ',', '.') ?>
        <th><?= number_format($pl['Gross Profit']['now'] ?? 0, 0, ',', '.') ?>
        <th><?= $diffs['Gross Profit'] = number_format(($pl['Gross Profit']['now'] ?? 0) - ($pl['Gross Profit']['old'] ?? 0), 0, ',', '.') ?>
        <th><?= !isset($pl['Gross Profit']['old']) ? 0 : number_format((($pl['Gross Profit']['now'] ?? 0)/$pl['Gross Profit']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <th>Gross Profit Margin
        <th><?= number_format(($pl['Gross Profit Margin']['old'] ?? 0) * 100, 0, ',', '.') ?> %
        <th><?= number_format(($pl['Gross Profit Margin']['now'] ?? 0) * 100, 0, ',', '.') ?> %
        <th><?= number_format((($pl['Gross Profit Margin']['now'] ?? 0) - ($pl['Gross Profit Margin']['old'] ?? 0)) * 100, 0, ',', '.') ?> %
        <th><?= !isset($pl['Gross Profit Margin']['old']) ? 0 : number_format((($pl['Gross Profit Margin']['now'] ?? 0)/$pl['Gross Profit Margin']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Freight
        <td><?= number_format($pl['Freight']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Freight']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['Freight']['now'] ?? 0) - ($pl['Freight']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Freight']['old']) ? 0 : number_format((($pl['Freight']['now'] ?? 0)/$pl['Freight']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Provisions
        <td><?= number_format($pl['Provisions']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Provisions']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['Provisions']['now'] ?? 0) - ($pl['Provisions']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Provisions']['old']) ? 0 : number_format((($pl['Provisions']['now'] ?? 0)/$pl['Provisions']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>External Seminars
        <td><?= number_format($pl['External Seminars']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['External Seminars']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['External Seminars']['now'] ?? 0) - ($pl['External Seminars']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['External Seminars']['old']) ? 0 : number_format((($pl['External Seminars']['now'] ?? 0)/$pl['External Seminars']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <th>Other Selling Expenses
        <th><?= number_format($pl['Other Selling Expenses']['old'] ?? 0, 0, ',', '.') ?>
        <th><?= number_format($pl['Other Selling Expenses']['now'] ?? 0, 0, ',', '.') ?>
        <th><?= $diffs['Selling Expenses'] = number_format(($pl['Other Selling Expenses']['now'] ?? 0) - ($pl['Other Selling Expenses']['old'] ?? 0), 0, ',', '.') ?>
        <th><?= !isset($pl['Other Selling Expenses']['old']) ? 0 : number_format((($pl['Other Selling Expenses']['now'] ?? 0)/$pl['Other Selling Expenses']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <th>Other Selling Expenses Margin
        <th><?= number_format(($pl['Other Selling Expenses Margin']['old'] ?? 0) * 100, 0, ',', '.') ?> %
        <th><?= number_format(($pl['Other Selling Expenses Margin']['now'] ?? 0) * 100, 0, ',', '.') ?> %
        <th><?= number_format((($pl['Other Selling Expenses Margin']['now'] ?? 0) - ($pl['Other Selling Expenses Margin']['old'] ?? 0)) * 100, 0, ',', '.') ?> %
        <th><?= !isset($pl['Other Selling Expenses Margin']['old']) ? 0 : number_format((($pl['Other Selling Expenses Margin']['now'] ?? 0)/$pl['Other Selling Expenses Margin']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Other Revenue
        <td><?= number_format($pl['Other Revenue']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Other Revenue']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= $diffs['Other Revenue'] = number_format(($pl['Other Revenue']['now'] ?? 0) - ($pl['Other Revenue']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Other Revenue']['old']) ? 0 : number_format((($pl['Other Revenue']['now'] ?? 0)/$pl['Other Revenue']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Wages & Salaries
        <td><?= number_format($pl['Wages & Salaries']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Wages & Salaries']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['Wages & Salaries']['now'] ?? 0) - ($pl['Wages & Salaries']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Wages & Salaries']['old']) ? 0 : number_format((($pl['Wages & Salaries']['now'] ?? 0)/$pl['Wages & Salaries']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Welfare Expenses
        <td><?= number_format($pl['Welfare Expenses']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Welfare Expenses']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['Welfare Expenses']['now'] ?? 0) - ($pl['Welfare Expenses']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Welfare Expenses']['old']) ? 0 : number_format((($pl['Welfare Expenses']['now'] ?? 0)/$pl['Welfare Expenses']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <th>Personnel
        <th><?= number_format($pl['Personnel']['old'] ?? 0, 0, ',', '.') ?>
        <th><?= number_format($pl['Personnel']['now'] ?? 0, 0, ',', '.') ?>
        <th><?= $diffs['Personnel'] = number_format(($pl['Personnel']['now'] ?? 0) - ($pl['Personnel']['old'] ?? 0), 0, ',', '.') ?>
        <th><?= !isset($pl['Personnel']['old']) ? 0 : number_format((($pl['Personnel']['now'] ?? 0)/$pl['Personnel']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <th>Personnel Margin
        <th><?= number_format(($pl['Personnel Margin']['old'] ?? 0) * 100, 0, ',', '.') ?> %
        <th><?= number_format(($pl['Personnel Margin']['now'] ?? 0) * 100, 0, ',', '.') ?> %
        <th><?= number_format((($pl['Personnel Margin']['now'] ?? 0) - ($pl['Personnel Margin']['old'] ?? 0)) * 100, 0, ',', '.') ?> %
        <th><?= !isset($pl['Personnel Margin']['old']) ? 0 : number_format((($pl['Personnel Margin']['now'] ?? 0)/$pl['Personnel Margin']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Marketing
        <td><?= number_format($pl['Marketing']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Marketing']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= $diffs['Marketing'] = number_format(($pl['Marketing']['now'] ?? 0) - ($pl['Marketing']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Marketing']['old']) ? 0 : number_format((($pl['Marketing']['now'] ?? 0)/$pl['Marketing']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Trade Fair
        <td><?= number_format($pl['Trade Fair']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Trade Fair']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= $diffs['Fair'] = number_format(($pl['Trade Fair']['now'] ?? 0) - ($pl['Trade Fair']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Trade Fair']['old']) ? 0 : number_format((($pl['Trade Fair']['now'] ?? 0)/$pl['Trade Fair']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Rental & Leasing
        <td><?= number_format($pl['Rental & Leasing']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Rental & Leasing']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= $diffs['Rental'] = number_format(($pl['Rental & Leasing']['now'] ?? 0) - ($pl['Rental & Leasing']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Rental & Leasing']['old']) ? 0 : number_format((($pl['Rental & Leasing']['now'] ?? 0)/$pl['Rental & Leasing']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Utilities
        <td><?= number_format($pl['Utilities']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Utilities']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= $diffs['Utilities'] = number_format(($pl['Utilities']['now'] ?? 0) - ($pl['Utilities']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Utilities']['old']) ? 0 : number_format((($pl['Utilities']['now'] ?? 0)/$pl['Utilities']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Repair/Maintenance
        <td><?= number_format($pl['Repair/Maintenance']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Repair/Maintenance']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= $diffs['Maintenance'] = number_format(($pl['Repair/Maintenance']['now'] ?? 0) - ($pl['Repair/Maintenance']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Repair/Maintenance']['old']) ? 0 : number_format((($pl['Repair/Maintenance']['now'] ?? 0)/$pl['Repair/Maintenance']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Carpool
        <td><?= number_format($pl['Carpool']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Carpool']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= $diffs['Carpool'] = number_format(($pl['Carpool']['now'] ?? 0) - ($pl['Carpool']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Carpool']['old']) ? 0 : number_format((($pl['Carpool']['now'] ?? 0)/$pl['Carpool']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Stationary Expenses
        <td><?= number_format($pl['Stationary Expenses']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Stationary Expenses']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= $diffs['Stationary'] = number_format(($pl['Stationary Expenses']['now'] ?? 0) - ($pl['Stationary Expenses']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Stationary Expenses']['old']) ? 0 : number_format((($pl['Stationary Expenses']['now'] ?? 0)/$pl['Stationary Expenses']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Communication
        <td><?= number_format($pl['Communication']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Communication']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= $diffs['Communication'] = number_format(($pl['Communication']['now'] ?? 0) - ($pl['Communication']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Communication']['old']) ? 0 : number_format((($pl['Communication']['now'] ?? 0)/$pl['Communication']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Travel Expenses
        <td><?= number_format($pl['Travel Expenses']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Travel Expenses']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= $diffs['Travel'] = number_format(($pl['Travel Expenses']['now'] ?? 0) - ($pl['Travel Expenses']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Travel Expenses']['old']) ? 0 : number_format((($pl['Travel Expenses']['now'] ?? 0)/$pl['Travel Expenses']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Entertainment
        <td><?= number_format($pl['Entertainment']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Entertainment']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= $diffs['Entertainment'] = number_format(($pl['Entertainment']['now'] ?? 0) - ($pl['Entertainment']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Entertainment']['old']) ? 0 : number_format((($pl['Entertainment']['now'] ?? 0)/$pl['Entertainment']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>External Consultants
        <td><?= number_format($pl['External Consultants']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['External Consultants']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= $diffs['Consultants'] = number_format(($pl['External Consultants']['now'] ?? 0) - ($pl['External Consultants']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['External Consultants']['old']) ? 0 : number_format((($pl['External Consultants']['now'] ?? 0)/$pl['External Consultants']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>R&D
        <td><?= number_format($pl['R&D']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['R&D']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= $diffs['R&D'] = number_format(($pl['R&D']['now'] ?? 0) - ($pl['R&D']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['R&D']['old']) ? 0 : number_format((($pl['R&D']['now'] ?? 0)/$pl['R&D']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Patents
        <td><?= number_format($pl['Patents']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Patents']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= $diffs['Patents'] = number_format(($pl['Patents']['now'] ?? 0) - ($pl['Patents']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Patents']['old']) ? 0 : number_format((($pl['Patents']['now'] ?? 0)/$pl['Patents']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Other Personnel Expenses
        <td><?= number_format($pl['Other Personnel Expenses']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Other Personnel Expenses']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= $diffs['Other Personnel'] = number_format(($pl['Other Personnel Expenses']['now'] ?? 0) - ($pl['Other Personnel Expenses']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Other Personnel Expenses']['old']) ? 0 : number_format((($pl['Other Personnel Expenses']['now'] ?? 0)/$pl['Other Personnel Expenses']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Other OPEX
        <td><?= number_format($pl['Other OPEX']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Other OPEX']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= $diffs['Other OPEX'] = number_format(($pl['Other OPEX']['now'] ?? 0) - ($pl['Other OPEX']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Other OPEX']['old']) ? 0 : number_format((($pl['Other OPEX']['now'] ?? 0)/$pl['Other OPEX']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Intercompany Expenses
        <td><?= number_format($pl['Intercompany Expenses']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Intercompany Expenses']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= $diffs['Interco Expenses'] = number_format(($pl['Intercompany Expenses']['now'] ?? 0) - ($pl['Intercompany Expenses']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Intercompany Expenses']['old']) ? 0 : number_format((($pl['Intercompany Expenses']['now'] ?? 0)/$pl['Intercompany Expenses']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Intercompany Revenue
        <td><?= number_format($pl['Intercompany Revenue']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Intercompany Revenue']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= $diffs['Interco Revenues'] = number_format(($pl['Intercompany Revenue']['now'] ?? 0) - ($pl['Intercompany Revenue']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Intercompany Revenue']['old']) ? 0 : number_format((($pl['Intercompany Revenue']['now'] ?? 0)/$pl['Intercompany Revenue']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Doubtful Accounts
        <td><?= number_format($pl['Doubtful Accounts']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Doubtful Accounts']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= $diffs['Doubtful'] = number_format(($pl['Doubtful Accounts']['now'] ?? 0) - ($pl['Doubtful Accounts']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Doubtful Accounts']['old']) || $pl['Doubtful Accounts']['old'] == 0 ? 0 : number_format((($pl['Doubtful Accounts']['now'] ?? 0)/$pl['Doubtful Accounts']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <th>Total Other OPEX
        <th><?= number_format($pl['Total Other OPEX']['old'] ?? 0, 0, ',', '.') ?>
        <th><?= number_format($pl['Total Other OPEX']['now'] ?? 0, 0, ',', '.') ?>
        <th><?= number_format(($pl['Total Other OPEX']['now'] ?? 0) - ($pl['Total Other OPEX']['old'] ?? 0), 0, ',', '.') ?>
        <th><?= !isset($pl['Total Other OPEX']['old']) ? 0 : number_format((($pl['Total Other OPEX']['now'] ?? 0)/$pl['Total Other OPEX']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <th>Total Other OPEX Margin
        <th><?= number_format(($pl['Total Other OPEX Margin']['old'] ?? 0) * 100, 0, ',', '.') ?> %
        <th><?= number_format(($pl['Total Other OPEX Margin']['now'] ?? 0) * 100, 0, ',', '.') ?> %
        <th><?= number_format((($pl['Total Other OPEX Margin']['now'] ?? 0) - ($pl['Total Other OPEX Margin']['old'] ?? 0)) * 100, 0, ',', '.') ?> %
        <th><?= !isset($pl['Total Other OPEX Margin']['old']) ? 0 : number_format((($pl['Total Other OPEX Margin']['now'] ?? 0)/$pl['Total Other OPEX Margin']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <th>EBITDA
        <th><?= number_format($pl['EBITDA']['old'] ?? 0, 0, ',', '.') ?>
        <th><?= number_format($pl['EBITDA']['now'] ?? 0, 0, ',', '.') ?>
        <th><?= number_format(($pl['EBITDA']['now'] ?? 0) - ($pl['EBITDA']['old'] ?? 0), 0, ',', '.') ?>
        <th><?= !isset($pl['EBITDA']['old']) ? 0 : number_format((($pl['EBITDA']['now'] ?? 0)/$pl['EBITDA']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <th>EBITDA Margin
        <th><?= number_format(($pl['EBITDA Margin']['old'] ?? 0) * 100, 0, ',', '.') ?> %
        <th><?= number_format(($pl['EBITDA Margin']['now'] ?? 0) * 100, 0, ',', '.') ?> %
        <th><?= number_format((($pl['EBITDA Margin']['now'] ?? 0) - ($pl['EBITDA Margin']['old'] ?? 0)) * 100, 0, ',', '.') ?> %
        <th><?= !isset($pl['EBITDA Margin']['old']) ? 0 : number_format((($pl['EBITDA Margin']['now'] ?? 0)/$pl['EBITDA Margin']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Depreciation
        <td><?= number_format($pl['Depreciation']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Depreciation']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= $diffs['Depreciation'] = number_format(($pl['Depreciation']['now'] ?? 0) - ($pl['Depreciation']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Depreciation']['old']) ? 0 : number_format((($pl['Depreciation']['now'] ?? 0)/$pl['Depreciation']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <th>Operating Income (EBIT)
        <th><?= number_format($pl['Operating Income (EBIT)']['old'] ?? 0, 0, ',', '.') ?>
        <th><?= number_format($pl['Operating Income (EBIT)']['now'] ?? 0, 0, ',', '.') ?>
        <th><?= number_format(($pl['Operating Income (EBIT)']['now'] ?? 0) - ($pl['Operating Income (EBIT)']['old'] ?? 0), 0, ',', '.') ?>
        <th><?= !isset($pl['Operating Income (EBIT)']['old']) ? 0 : number_format((($pl['Operating Income (EBIT)']['now'] ?? 0)/$pl['Operating Income (EBIT)']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <th>EBIT Margin
        <th><?= number_format(($pl['EBIT Margin']['old'] ?? 0) * 100, 0, ',', '.') ?> %
        <th><?= number_format(($pl['EBIT Margin']['now'] ?? 0) * 100, 0, ',', '.') ?> %
        <th><?= number_format((($pl['EBIT Margin']['now'] ?? 0) - ($pl['EBIT Margin']['old'] ?? 0)) * 100, 0, ',', '.') ?> %
        <th><?= !isset($pl['EBIT Margin']['old']) ? 0 : number_format((($pl['EBIT Margin']['now'] ?? 0)/$pl['EBIT Margin']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Interest Revenue
        <td><?= number_format($pl['Interest Revenue']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Interest Revenue']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['Interest Revenue']['now'] ?? 0) - ($pl['Interest Revenue']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Interest Revenue']['old']) ? 0 : number_format((($pl['Interest Revenue']['now'] ?? 0)/$pl['Interest Revenue']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Interest Expenses
        <td><?= number_format($pl['Interest Expenses']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Interest Expenses']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['Interest Expenses']['now'] ?? 0) - ($pl['Interest Expenses']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Interest Expenses']['old']) ? 0 : number_format((($pl['Interest Expenses']['now'] ?? 0)/$pl['Interest Expenses']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <th>EBT
        <th><?= number_format($pl['EBT']['old'] ?? 0, 0, ',', '.') ?>
        <th><?= number_format($pl['EBT']['now'] ?? 0, 0, ',', '.') ?>
        <th><?= number_format(($pl['EBT']['now'] ?? 0) - ($pl['EBT']['old'] ?? 0), 0, ',', '.') ?>
        <th><?= !isset($pl['EBT']['old']) ? 0 : number_format((($pl['EBT']['now'] ?? 0)/$pl['EBT']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <th>EBT Margin
        <th><?= number_format(($pl['EBT Margin']['old'] ?? 0) * 100, 0, ',', '.') ?> %
        <th><?= number_format(($pl['EBT Margin']['now'] ?? 0) * 100, 0, ',', '.') ?> %
        <th><?= number_format((($pl['EBT Margin']['now'] ?? 0) - ($pl['EBT Margin']['old'] ?? 0)) * 100, 0, ',', '.') ?> %
        <th><?= !isset($pl['EBT Margin']['old']) ? 0 : number_format((($pl['EBT Margin']['now'] ?? 0)/$pl['EBT Margin']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Taxes
        <td><?= number_format($pl['Taxes']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Taxes']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['Taxes']['now'] ?? 0) - ($pl['Taxes']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Taxes']['old']) ? 0 : number_format((($pl['Taxes']['now'] ?? 0)/$pl['Taxes']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Transfer Of Profits
        <td><?= number_format($pl['Transfer Of Profits']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Transfer Of Profits']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['Transfer Of Profits']['now'] ?? 0) - ($pl['Transfer Of Profits']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Transfer Of Profits']['old']) ? 0 : number_format((($pl['Transfer Of Profits']['now'] ?? 0)/$pl['Transfer Of Profits']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <th>Net Income (EAT)
        <th><?= number_format($pl['Net Income (EAT)']['old'] ?? 0, 0, ',', '.') ?>
        <th><?= number_format($pl['Net Income (EAT)']['now'] ?? 0, 0, ',', '.') ?>
        <th><?= number_format(($pl['Net Income (EAT)']['now'] ?? 0) - ($pl['Net Income (EAT)']['old'] ?? 0), 0, ',', '.') ?>
        <th><?= !isset($pl['Net Income (EAT)']['old']) ? 0 : number_format((($pl['Net Income (EAT)']['now'] ?? 0)/$pl['Net Income (EAT)']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <th>Net Income Margin
        <th><?= number_format(($pl['Net Income Margin']['old'] ?? 0) * 100, 0, ',', '.') ?> %
        <th><?= number_format(($pl['Net Income Margin']['now'] ?? 0) * 100, 0, ',', '.') ?> %
        <th><?= number_format((($pl['Net Income Margin']['now'] ?? 0) - ($pl['Net Income Margin']['old'] ?? 0)) * 100, 0, ',', '.') ?> %
        <th><?= !isset($pl['Net Income Margin']['old']) ? 0 : number_format((($pl['Net Income Margin']['now'] ?? 0)/$pl['Net Income Margin']['old'] - 1)*100, 2, ',', '.')?> %
</table>

<?= $this->getData('type') === 'accumulated' ? '<svg class="chart"></svg>' : ''; ?>

<div class="clear"></div>

<script>
var margin = {top: 20, right: 30, bottom: 100, left: 40},
    width = 960 - margin.left - margin.right,
    height = 400 - margin.top - margin.bottom,
    padding = 0.3;

var x = d3.scale.ordinal()
    .rangeRoundBands([0, width], padding);

var y = d3.scale.linear()
    .range([height, 0]);

var xAxis = d3.svg.axis()
    .scale(x)
    .orient("bottom");

var yAxis = d3.svg.axis()
    .scale(y)
    .orient("left")
    .tickFormat(function(d) { return dollarFormatter(d); });

var chart = d3.select(".chart")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
  .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

var data = [
        {name: 'EBIT PY', value: <?= $pl['Operating Income (EBIT)']['old'] ?? 0; ?>},
        <?php foreach($diffs as $name => $value) : ?>
            {name: '<?= $name; ?>', value: <?= str_replace(',', '.', str_replace('.', '', $value)); ?>},
        <?php endforeach; ?>
    ];

  // Transform data (i.e., finding cumulative values and total) for easier charting
  var cumulative = 0;
  for (var i = 0; i < data.length; i++) {
    data[i].start = cumulative;
    cumulative += data[i].value;
    data[i].end = cumulative;

    data[i].class = i === 0 ? 'total' : ( data[i].value >= 0 ) ? 'positive' : 'negative'
  }
  data.push({
    name: 'EBIT',
    end: cumulative,
    start: 0,
    class: 'total'
  });

  x.domain(data.map(function(d) { return d.name; }));
  y.domain([0, d3.max(data, function(d) { return d.end; })]);

  chart.append("g")
      .attr("class", "x axis")
      .attr("transform", "translate(0," + height + ")")
      .call(xAxis)
      .selectAll("text")  
            .style("text-anchor", "end")
            .attr("dx", "-.8em")
            .attr("dy", ".15em")
            .attr("transform", "rotate(-65)" );

  chart.append("g")
      .attr("class", "y axis")
      .call(yAxis);

  var bar = chart.selectAll(".bar")
      .data(data)
    .enter().append("g")
      .attr("class", function(d) { return "bar " + d.class })
      .attr("transform", function(d) { return "translate(" + x(d.name) + ",0)"; });

  bar.append("rect")
      .attr("y", function(d) { return y( Math.max(d.start, d.end) ); })
      .attr("height", function(d) { return Math.abs( y(d.start) - y(d.end) ); })
      .attr("width", x.rangeBand());

  bar.append("text")
      .attr("x", x.rangeBand() / 2)
      .attr("y", function(d) { return y(d.end) + 5; })
      .attr("dy", function(d) { return ((d.class=='negative') ? '-' : '') + ".75em" })
      .text(function(d) { return dollarFormatter(d.end - d.start);});

  bar.filter(function(d) { return d.class != "total" }).append("line")
      .attr("class", "connector")
      .attr("x1", x.rangeBand() + 5 )
      .attr("y1", function(d) { return y(d.end) } )
      .attr("x2", x.rangeBand() / ( 1 - padding) - 5 )
      .attr("y2", function(d) { return y(d.end) } );

function type(d) {
  d.value = +d.value;
  return d;
}

function dollarFormatter(n) {
  n = Math.round(n);
  var result = n;
  if (Math.abs(n) > 1000) {
    result = Math.round(n/1000) + 'K';
  }
  return '€' + result;
}

</script>