<table>
    <caption>Customer sales list</caption>
    <thead>
    <tr>
        <th>No
        <th>Customer
        <th>Sales
        <th>%
        <th>No
        <th>Customer
        <th>Sales
        <th>%
    <tbody>
    <?php for($i = 1; $i < 11; $i++) : ?>
    <tr>
        <td><?= $i; ?>
        <td>
        <td>
        <td>
        <td><?= $i; ?>
        <td>
        <td>
        <td>
    <?php endfor; ?>
    <tr>
        <td>
        <td>Other
        <td>
        <td>
        <td>
        <td>Other
        <td>
        <td>
    <tr>
        <th>小計
        <th>Subtotal
        <th>
        <th>
        <th>小計
        <th>Subtotal
        <th>
        <th>
    <tr>
        <th>内部取引消去
        <th>Interco
        <th>
        <th>
        <th>内部取引消去
        <th>Interco
        <th>
        <th>
    <tr>
        <th>合計
        <th>Total
        <th>
        <th>
        <th>合計
        <th>Total
        <th>
        <th>
</table>