<?php
return [
    [
        'method' => 'GET',
        'pattern' => '/admin_api/firm-search/{type}/{page:\d+}/{search}',
        'handler' => ['Admin\\Firm', 'getFirmsList']
    ],
    [
        'method' => 'GET',
        'pattern' => '/пожаловаться/{id:\d+}',
        'handler' => ['Pages', 'abuse']
    ],
    [
        'method' => 'GET',
        'pattern' => '/abuse/{id:\d+}',
        'handler' => ['Pages', 'abuse']
    ],
    [
        'method' => 'POST',
        'pattern' => '/пожаловаться/{id:\d+}',
        'handler' => ['Pages', 'abuse_handler']
    ],
    [
        'method' => 'GET',
        'pattern' => '/search/front-ajax/{query}',
        'handler' => ['Search\\Search', 'frontAjax']
    ],
    [
        'method' => 'GET',
        'pattern' => '/admin_api/review-search/{type}/{page:\d+}/{search}',
        'handler' => ['Admin\\Review', 'getReviewsList']
    ],
    [
        'method' => 'POST',
        'pattern' => '/admin_api/auth/logout',
        'handler' => ['Admin\\Auth', 'logout']
    ],
    [
        'method' => 'GET',
        'pattern' => '/ajax/get-city/{id:\d+}',
        'handler' => ['Ajax', 'getCity']
    ],
    [
        'method' => 'POST',
        'pattern' => '/admin_api/firm/upload_logo/{id:\d+}',
        'handler' => ['Admin\\Firm', 'uploadLogo']
    ],
    [
        'method' => 'GET',
        'pattern' => '/admin_api/firm/delete_logo/{id:\d+}',
        'handler' => ['Admin\\Firm', 'deleteLogo']
    ],
];
