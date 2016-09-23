<?php
$pl = $this->getData('accountPositions');
?>
<h1>P&L</h1>

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
        <td>Other Revenue
        <td><?= number_format($pl['Other Revenue']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Other Revenue']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['Other Revenue']['now'] ?? 0) - ($pl['Other Revenue']['old'] ?? 0), 0, ',', '.') ?>
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
        <td>Marketing
        <td><?= number_format($pl['Marketing']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Marketing']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['Marketing']['now'] ?? 0) - ($pl['Marketing']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Marketing']['old']) ? 0 : number_format((($pl['Marketing']['now'] ?? 0)/$pl['Marketing']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Trade Fair
        <td><?= number_format($pl['Trade Fair']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Trade Fair']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['Trade Fair']['now'] ?? 0) - ($pl['Trade Fair']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Trade Fair']['old']) ? 0 : number_format((($pl['Trade Fair']['now'] ?? 0)/$pl['Trade Fair']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Rental & Leasing
        <td><?= number_format($pl['Rental & Leasing']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Rental & Leasing']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['Rental & Leasing']['now'] ?? 0) - ($pl['Rental & Leasing']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Rental & Leasing']['old']) ? 0 : number_format((($pl['Rental & Leasing']['now'] ?? 0)/$pl['Rental & Leasing']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Utilities
        <td><?= number_format($pl['Utilities']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Utilities']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['Utilities']['now'] ?? 0) - ($pl['Utilities']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Utilities']['old']) ? 0 : number_format((($pl['Utilities']['now'] ?? 0)/$pl['Utilities']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Repair/Maintenance
        <td><?= number_format($pl['Repair/Maintenance']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Repair/Maintenance']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['Repair/Maintenance']['now'] ?? 0) - ($pl['Repair/Maintenance']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Repair/Maintenance']['old']) ? 0 : number_format((($pl['Repair/Maintenance']['now'] ?? 0)/$pl['Repair/Maintenance']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Carpool
        <td><?= number_format($pl['Carpool']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Carpool']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['Carpool']['now'] ?? 0) - ($pl['Carpool']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Carpool']['old']) ? 0 : number_format((($pl['Carpool']['now'] ?? 0)/$pl['Carpool']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Stationary Expenses
        <td><?= number_format($pl['Stationary Expenses']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Stationary Expenses']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['Stationary Expenses']['now'] ?? 0) - ($pl['Stationary Expenses']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Stationary Expenses']['old']) ? 0 : number_format((($pl['Stationary Expenses']['now'] ?? 0)/$pl['Stationary Expenses']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Communication
        <td><?= number_format($pl['Communication']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Communication']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['Communication']['now'] ?? 0) - ($pl['Communication']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Communication']['old']) ? 0 : number_format((($pl['Communication']['now'] ?? 0)/$pl['Communication']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Travel Expenses
        <td><?= number_format($pl['Travel Expenses']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Travel Expenses']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['Travel Expenses']['now'] ?? 0) - ($pl['Travel Expenses']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Travel Expenses']['old']) ? 0 : number_format((($pl['Travel Expenses']['now'] ?? 0)/$pl['Travel Expenses']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Entertainment
        <td><?= number_format($pl['Entertainment']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Entertainment']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['Entertainment']['now'] ?? 0) - ($pl['Entertainment']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Entertainment']['old']) ? 0 : number_format((($pl['Entertainment']['now'] ?? 0)/$pl['Entertainment']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>External Consultants
        <td><?= number_format($pl['External Consultants']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['External Consultants']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['External Consultants']['now'] ?? 0) - ($pl['External Consultants']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['External Consultants']['old']) ? 0 : number_format((($pl['External Consultants']['now'] ?? 0)/$pl['External Consultants']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>R&D
        <td><?= number_format($pl['R&D']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['R&D']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['R&D']['now'] ?? 0) - ($pl['R&D']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['R&D']['old']) ? 0 : number_format((($pl['R&D']['now'] ?? 0)/$pl['R&D']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Patents
        <td><?= number_format($pl['Patents']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Patents']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['Patents']['now'] ?? 0) - ($pl['Patents']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Patents']['old']) ? 0 : number_format((($pl['Patents']['now'] ?? 0)/$pl['Patents']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Other Personnel Expenses
        <td><?= number_format($pl['Other Personnel Expenses']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Other Personnel Expenses']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['Other Personnel Expenses']['now'] ?? 0) - ($pl['Other Personnel Expenses']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Other Personnel Expenses']['old']) ? 0 : number_format((($pl['Other Personnel Expenses']['now'] ?? 0)/$pl['Other Personnel Expenses']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Other OPEX
        <td><?= number_format($pl['Other OPEX']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Other OPEX']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['Other OPEX']['now'] ?? 0) - ($pl['Other OPEX']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Other OPEX']['old']) ? 0 : number_format((($pl['Other OPEX']['now'] ?? 0)/$pl['Other OPEX']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Intercompany Expenses
        <td><?= number_format($pl['Intercompany Expenses']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Intercompany Expenses']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['Intercompany Expenses']['now'] ?? 0) - ($pl['Intercompany Expenses']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Intercompany Expenses']['old']) ? 0 : number_format((($pl['Intercompany Expenses']['now'] ?? 0)/$pl['Intercompany Expenses']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Intercompany Revenue
        <td><?= number_format($pl['Intercompany Revenue']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Intercompany Revenue']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['Intercompany Revenue']['now'] ?? 0) - ($pl['Intercompany Revenue']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Intercompany Revenue']['old']) ? 0 : number_format((($pl['Intercompany Revenue']['now'] ?? 0)/$pl['Intercompany Revenue']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Doubtful Accounts
        <td><?= number_format($pl['Doubtful Accounts']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Doubtful Accounts']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['Doubtful Accounts']['now'] ?? 0) - ($pl['Doubtful Accounts']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Doubtful Accounts']['old']) ? 0 : number_format((($pl['Doubtful Accounts']['now'] ?? 0)/$pl['Doubtful Accounts']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <td>Depreciation
        <td><?= number_format($pl['Depreciation']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Depreciation']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['Depreciation']['now'] ?? 0) - ($pl['Depreciation']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Depreciation']['old']) ? 0 : number_format((($pl['Depreciation']['now'] ?? 0)/$pl['Depreciation']['old'] - 1)*100, 2, ',', '.')?> %
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
</table>

<div class="clear"></div>