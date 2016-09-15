<?php
$sales = $this->getData('sales');
$salesLast = $this->getData('salesLast');
$salesAcc = $this->getData('salesAcc');
$salesAccLast = $this->getData('salesAccLast');
$today = $this->getData('currentMonth');
?>
<table>
    <caption>Sales By Month</caption>
    <thead>
    <tr>
        <th>Month
        <th>Last Year
        <th>Current
        <th>Diff
        <th>Diff %
    <tbody>
    <?php for($i = 1; $i <= 12; $i++) : ?>
    <tr<?= $i === $today ? ' class="bold"' : '';?>>
        <td><?= $i; ?>
        <td><?= !isset($salesLast[$i]) ? '' : number_format($salesLast[$i], 0, ',', '.'); ?>
        <td><?= !isset($sales[$i]) ? '' : number_format($sales[$i], 0, ',', '.'); ?>
        <td><?= number_format(($sales[$i] ?? 0) - ($salesLast[$i] ?? 0), 0, ',', '.'); ?>
        <td><?= number_format(($salesLast[$i] ?? 0) == 0 ? 0 : (($sales[$i] ?? 0)/($salesLast[$i] ?? 0) - 1) * 100, 2, ',', '.'); ?> %
    <?php endfor; ?>
    <tr>
        <th>Current
        <th><?= number_format($salesAccLast[$today], 0, ',', '.'); ?>
        <th><?= number_format($salesAcc[$days], 0, ',', '.'); ?>
        <th><?= number_format($salesAcc[$days] - $salesAccLast[$today], 0, ',', '.'); ?>
        <th><?= number_format($salesAcc[$days] == 0 ? 0 : ($salesAcc[$days]/$salesAccLast[$today] - 1) * 100, 2, ',', '.'); ?> %
</script>