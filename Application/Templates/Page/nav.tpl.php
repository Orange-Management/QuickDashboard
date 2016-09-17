<div class="floater">
    <nav>
        <ul>
            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}?u={?u}'); ?>">Overview</a>
            <li>Sales
                <ul>
                    <li>Overview
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/history?{?}'); ?>">Month</a>
                            <li><a href="">Year</a>
                        </ul>
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
                    <li>Articles
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/articles?{?}&i=month'); ?>">Month</a>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/articles?{?}&i=year'); ?>">Year</a>
                        </ul>
                    <li>Customers
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/customers?{?}'); ?>">Month</a>
                            <li><a href="">Year</a>
                        </ul>
                    <li>Sales Reps
                        <ul>
                            <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}sales/reps?{?}'); ?>">Month</a>
                            <li><a href="">Year</a>
                        </ul>
                </ul>
            <li>Reporting
                <ul>
                    <li><a href="">P&L</a>
                    <li><a href="">KPI</a>
                    <li><a href="">Sales & EBIT</a>
                </ul>
            <li>Analysis
                <ul>
                    <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}analysis/reps?{?}'); ?>">Sales Rep</a>
                    <li><a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}analysis/article?{?}'); ?>">Article</a>
                    <li><a href="">Customer</a>
                    <li><a href="">Account</a>
                    <li><a href="">Cost Center</a>
                    <li><a href="">Cost Object</a>
                </ul>
            <li><a href="">Risk Management</a>
    </nav>
</div>