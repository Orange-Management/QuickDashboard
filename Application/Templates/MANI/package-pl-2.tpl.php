<form method="GET" action="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}analysis/customer'); ?>">
    <table>
        <tr>
            <td><label for="segment">Segment ID:</label>
            <td><select id="segment" name="segment">
                <option value="0">All
                <?php foreach(\QuickDashboard\Application\Models\StructureDefinitions::NAMING as $id => $name) : ?>
                <option value="<?= $id; ?>"<?php if(((int) $this->request->getData('segment')) == (int) $id) { echo ' selected'; $gId = $id; $gName = $name; }; ?>><?= $id; ?> - <?= $name; ?>
            <?php endforeach; ?>
            </select>
            <td style="width: 100%">
                <input type="hidden" name="u" value="<?= $this->request->getData('u') ?? ''; ?>">
                <input type="hidden" name="t" value="<?= $this->request->getData('t') ?? ''; ?>">
        <tr>
            <td colspan="5"><input type="submit" value="Analyse">
    </table>
</form>

<table>
    <caption>P&L</caption>
    <thead>
    <tr>
        <th>Title
        <th>Jul.
        <th>Aug.
        <th>Sep.
        <th>Oct.
        <th>Nov.
        <th>Dec.
        <th>Jan.
        <th>Feb.
        <th>Mar.
        <th>Apr.
        <th>May
        <th>Jun.
    <tbody>
    <tr>
        <th>Sales(AM)
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
    <tr>
        <th>Sales(SM)
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
    <tr>
        <td>Material(AM)
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
    <tr>
        <td>Material(SM)
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
    <tr>
        <td>Labor(AM)
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
    <tr>
        <td>Labor(SM)
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
    <tr>
        <td>Subcontractor(AM)
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
    <tr>
        <td>Subcontractor(SM)
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
    <tr>
        <td>Production(AM)
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
    <tr>
        <td>Production(SM)
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
    <tr>
        <th>Manufacturing(AM)
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
    <tr>
        <th>Manufacturing(SM)
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
    <tr>
        <td>Work-in-progress
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
    <tr>
        <td>Change in inventory
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
    <tr>
        <th>COGM(AM)
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
    <tr>
        <th>COGM(SM)
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
    <tr>
        <td>Finished goods
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
    <tr>
        <td>Change in inventory
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
    <tr>
        <td>Merchandise purchase
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
    <tr>
        <td>Change in inventory
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
    <tr>
        <th>COGS(AM)
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
    <tr>
        <th>COGS(SM)
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
    <tr>
        <th>Gross Profit(AM)
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
    <tr>
        <th>Gross Profit(SM)
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
    <tr>
        <td>Selling HR(AM)
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
    <tr>
        <td>Selling HR(SM)
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
    <tr>
        <td>Selling Other(AM)
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
    <tr>
        <td>Selling Other(SM)
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
    <tr>
        <th>Selling Total(AM)
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
    <tr>
        <th>Selling Total(SM)
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
    <tr>
        <td>R&D HR(AM)
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
    <tr>
        <td>R&D HR(SM)
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
    <tr>
        <td>R&D Other(AM)
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
    <tr>
        <td>R&D Other(SM)
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
    <tr>
        <th>R&D Total(AM)
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
    <tr>
        <th>R&D Total(SM)
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
    <tr>
        <td>G&A HR(AM)
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
    <tr>
        <td>G&A HR(SM)
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
    <tr>
        <td>G&A Other(AM)
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
    <tr>
        <td>G&A Other(SM)
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
    <tr>
        <th>G&A Total(AM)
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
    <tr>
        <th>G&A Total(SM)
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
    <tr>
        <th>Total(AM)
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
    <tr>
        <th>Total(SM)
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
    <tr>
        <th>Operating inc.(AM)
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
    <tr>
        <th>Operating inc.(SM)
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
    <tr>
        <td>Non-Operating inc.(AM)
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
    <tr>
        <td>Non-Operating inc.(SM)
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
    <tr>
        <th>Ordinary inc.(AM)
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
    <tr>
        <th>Ordinary inc.(SM)
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
    <tr>
        <td>Extraordinary.(AM)
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
    <tr>
        <td>Extraordinary.(SM)
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
        <td>
    <tr>
        <th>EBIT(AM)
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
    <tr>
        <th>EBIT(SM)
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
    <tr>
        <th>Net income(AM)
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
    <tr>
        <th>Net income(SM)
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
        <th>
</table>