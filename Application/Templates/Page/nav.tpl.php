<div class="floater">
    <nav>
        <ul>
            <li><i class="fa fa-home"></i> <a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}?u={?u}'); ?>">Overview</a>
            <li><i class="fa fa-line-chart"></i> Sales
                <ul>
                    <li>List
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/list?{?}&i=month'); ?>">Month</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/list?{?}&i=year'); ?>">Year</a>
                        </ul>
                    <li>Location
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/location?{?}&i=month'); ?>">Month</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/location?{?}&i=year'); ?>">Year</a>
                        </ul>
                    <li>Segmentation
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/segmentation?{?}&i=month'); ?>">Month</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/segmentation?{?}&i=year'); ?>">Year</a>
                        </ul>
                    <li>Customers
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/customers?{?}&i=month'); ?>">Month</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/customers?{?}&i=year'); ?>">Year</a>
                        </ul>
                    <li>Sales Reps
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/reps?{?}&i=month'); ?>">Month</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/reps?{?}&i=year'); ?>">Year</a>
                        </ul>
                </ul>
            <li><i class="fa fa-bar-chart"></i> Reporting
                <ul>
                    <li>P&L
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}reporting/pl?{?}&i=month'); ?>">Month</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}reporting/pl?{?}&i=year'); ?>">Year</a>
                        </ul>
                    <li>Gross Profit
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}reporting/profit?{?}&i=month'); ?>">Month</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}reporting/profit?{?}&i=year'); ?>">Year</a>
                        </ul>
                    <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}reporting/ebit?{?}'); ?>">EBIT</a>
                </ul>
                <li><i class="fa fa-bar-search"></i> Analysis
                    <ul>
                        <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}analysis/customer?{?}'); ?>">Customer</a>
                    </ul>
                <li><li><li><li>
    </nav>
</div>