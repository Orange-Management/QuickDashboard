<?php

use phpOMS\Router\RouteVerb;

return [
	'^(\/*\?.*)*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\OverviewController:showOverview',
            'verb' => RouteVerb::GET,
        ],
    ],

    '^sales/list.*?i=month.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\SalesController:showListMonth',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/list.*?i=year.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\SalesController:showListYear',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/location.*?i=month.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\SalesController:showLocationMonth',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/location.*?i=year.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\SalesController:showLocationYear',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/segmentation.*?i=month.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\SalesController:showArticleMonth',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/segmentation.*?i=year.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\SalesController:showArticleYear',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/customers.*?i=month.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\SalesController:showCustomersMonth',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/customers.*?i=year.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\SalesController:showCustomersYear',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/reps.*?i=month.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\SalesController:showRepsMonth',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/reps.*?i=year.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\SalesController:showRepsYear',
            'verb' => RouteVerb::GET,
        ],
    ],

    '^analysis/reps.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\AnalysisController:showAnalysisReps',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^analysis/customer.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\AnalysisController:showAnalysisCustomer',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^analysis/segmentation.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\AnalysisController:showAnalysisSegmentation',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^analysis/location.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\AnalysisController:showAnalysisLocation',
            'verb' => RouteVerb::GET,
        ],
    ],

    '^reporting/pl.*?i=month.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\ReportingController:showPLMonth',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^reporting/pl.*?i=year.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\ReportingController:showPLYear',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^reporting/profit.*?i=month.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\ReportingController:showArticleProfitMonth',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^reporting/profit.*?i=year.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\ReportingController:showArticleProfitYear',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^reporting/ebit.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\ReportingController:showEBIT',
            'verb' => RouteVerb::GET,
        ],
    ],
];