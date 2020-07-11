<?php

namespace wMVC;

define('DEBUG', isset($_COOKIE['__DBG__']));
define('DEVELOPMENT', '1');
define('PRODUCTION', '2');
define('ENVIRONMENT', PRODUCTION);

define('MAIN_DOMAIN', 'xn--80adsqinks2h.xn--p1ai');
mb_internal_encoding("UTF-8");
error_reporting(1);
ini_set('display_errors', 1);
if (ENVIRONMENT == DEVELOPMENT OR DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}
//db config
define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'tvoyafirma');

define('THUMBS_PATH', ROOT_PATH . 'public' . DIRECTORY_SEPARATOR . 'asset' . DIRECTORY_SEPARATOR . 'thumb' . DIRECTORY_SEPARATOR);

define('HTTP_HOST', $_SERVER['HTTP_HOST']);

//controller, model and views paths
define('CONTROLLER_PATH', APP_PATH . 'controller' . DIRECTORY_SEPARATOR);
define('MODEL_PATH', APP_PATH . 'model' . DIRECTORY_SEPARATOR);
define('TEMPLATES_PATH', APP_PATH . 'templates' . DIRECTORY_SEPARATOR);
define('SX_GEO_PATH', APP_PATH . 'SxGeoCity.dat');
define('HELPER_PATH', APP_PATH . 'entity' . DIRECTORY_SEPARATOR);
define('ASSET_PATH', ROOT_PATH . 'public/asset' . DIRECTORY_SEPARATOR);

define('SESSION_NAME', 'wmvc_eng_session');


define('BAD_WORDS_TESTING', 0); // константа для тестов
define('ADSENSE_CHECK_DRUGS', 100);
define('ADSENSE_CHECK_PORN', 101);
define('ADSENSE_CHECK_PHPVERSION', '5.3');
define('ADSENSE_CHECK_DRUPAL_EXCLUDE', 'form');
define('ADSENSE_CHECK_DRUPAL_CATEGORY_FIELD', 'field_category'); // название поля категория в ноде
define('ADSENSE_CHECK_DRUPAL_KEYWORDS_FIELD', 'field_relation'); // название поля с ключевыми словами в ноде
define('ADSENSE_CHECK_DRUPAL_PORN_CATEGORY', 138497); // tid категории для взрослых
define('ADSENSE_CHECK_BANLIST_TABLE', 'custom_ip_banlist'); // название таблицы с айпишниками
define('COMMENT_KOTEL_ACCESS_KEY', '7z8xPzmYRm3YrlgtwbdcJkUGDUnAcufjW'); // Ключ к котлу отзывов

date_default_timezone_set('Asia/Yekaterinburg');

Class Config
{
    static $cron_key = 'NaIMaSP5CrFyDkIBZCfuHZ3JS1Zw9G3H9asQEVN1I64RcFlZG87g6uxE5Ktv';
    static $site_name = 'ТвояФирма.РФ';
    static $site_url = 'твояфирма.рф';
    static $site_url_puny = 'xn--80adsqinks2h.xn--p1ai';
    static $google_search = '015878195270008352778:gaeimerf0ao';
    static $mail
        = [
            'admin'     => 'voinkov@saytum.ru',
            'moderator' => 'spravkarf.moderator@gmail.com',
            'project'   => 'tvoyafirma@ya.ru',
            'noreply'   => 'tvoyafirma@gmail.com'
        ];

    // 1st matching route will be used, means

    static $routes
        = [

            [
                'method'  => 'GET',
                'pattern' => '/restore',
                'handler' => ['User', 'restore']
            ],
            [
                'method'  => 'POST',
                'pattern' => '/restore',
                'handler' => ['User', 'restore_post']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/restore/{secret}',
                'handler' => ['User', 'restore_handler']
            ],
            [
                'method'  => 'POST',
                'pattern' => '/restore/{secret}',
                'handler' => ['User', 'restore_handler']
            ],
            
            [
                'method'  => 'GET',
                'pattern' => '/get-urls',
                'handler' => ['Index', 'automaticTest']
            ],
            [
                'method'  => 'POST',
                'pattern' => '/get-urls',
                'handler' => ['Index', 'automaticTest']
            ],
            ['method'  => 'GET',
             'pattern' => '/up',
             'handler' => ['AdvServer', 'showFront'],
            ],

            [
                'method'  => 'POST',
                'pattern' => '/api/kotel',
                'handler' => ['Kotel', 'incoming']
            ],

            ['method'  => 'GET',
             'pattern' => '/up/{firmId:\d+}/checkout',
             'handler' => ['AdvServer', 'showCheckout'],
            ],
            ['method'  => 'POST',
             'pattern' => '/up/{firmId:\d+}/checkout',
             'handler' => ['AdvServer', 'submitCheckout'],
            ],

            ['method'  => 'POST',
             'pattern' => '/up/checkout/complete',
             'handler' => ['AdvServer', 'merchantSuccess'],
            ],

            ['method'  => 'POST',
             'pattern' => '/up/checkout/cancel',
             'handler' => ['AdvServer', 'merchantCancel'],
            ],

            ['method'  => 'POST',
             'pattern' => '/up/checkout/{secret}',
             'handler' => ['AdvServer', 'merchantResult'],
            ],

            ['method'  => 'GET',
             'pattern' => '/up/about',
             'handler' => ['AdvServer', 'aboutPage'],
            ],
            ['method'  => 'GET',
             'pattern' => '/up/contacts',
             'handler' => ['AdvServer', 'contactPage'],
            ],
            //Поднятие компании CLIENT
            ['method'  => 'GET',
             'pattern' => '/adv/up/{firmId:\d+}/{cash}/{month}/{email}/{tarif}',
             'handler' => ['AdvClient', 'firmUp'],
            ],
            ['method'  => 'GET',
             'pattern' => '/профиль/отзывы/{firmId:\d+}',
             'handler' => ['AdvClient', 'showReviews'],
            ],
            ['method'  => 'GET',
             'pattern' => '/профиль/отзыв/{commentId:\d+}/удалить',
             'handler' => ['AdvClient', 'deleteReview'],
            ],
            ['method'  => 'GET',
             'pattern' => '/adv/firm-info/{firmId:\d+}/{secret}',
             'handler' => ['AdvClient', 'getFirmInfo'],
            ],
            ['method'  => 'GET',
             'pattern' => '/adv/city-info/{secret}',
             'handler' => ['AdvClient', 'getCityInfo'],
            ],
            ['method'  => 'GET',
             'pattern' => '/cron/run/{secret}',
             'handler' => ['AdvCron', 'run'],
            ],
            ['method'  => 'GET',
             'pattern' => '/профиль/почта',
             'handler' => ['AdvClient', 'spamSettings'],
            ],
            ['method'  => 'POST',
             'pattern' => '/профиль/почта',
             'handler' => ['AdvClient', 'spamSettingsSubmit'],
            ],
            ['method'  => 'GET',
             'pattern' => '/профиль/фирма/удалить/{firmId:\d+}',
             'handler' => ['AdvClient', 'hideFirm'],
            ],


            //Статистика
            ['method'  => 'GET',
             'pattern' => '/stat/inc/{cityId}/{entityType}/{entityId:\d+}',
             'handler' => ['Stat', 'inc'],
            ],
            ['method'  => 'GET',
             'pattern' => '/stat/show/{firmId:\d+}',
             'handler' => ['Stat', 'show'],
            ],
            //ajax
            [
                'method'  => 'GET',
                'pattern' => '/ajax/user_company_region/{id:\d+}',
                'handler' => ['Ajax', 'user_company_region']
            ],

            [
                'method'  => 'GET',
                'pattern' => '/ajax/user_company_category/{id:\d+}',
                'handler' => ['Ajax', 'user_company_category']
            ],

            [
                'method'  => 'GET',
                'pattern' => '/ajax/user_company_keywords/{tag}',
                'handler' => ['Ajax', 'user_company_keywords']
            ],

            //админка
//            [
//                'method'  => 'GET',
//                'pattern' => '',
//                'handler' => ['Admin', '']
//            ],
            [
                'method'  => 'PUT',
                'pattern' => '/admin_api/auth',
                'handler' => ['Admin\\Auth', 'auth']
            ],
            [
                'method'  => 'POST',
                'pattern' => '/admin_api/auth',
                'handler' => ['Admin\\Auth', 'checkAuth']
            ],
            [
                'method'  => 'DELETE',
                'pattern' => '/admin_api/auth',
                'handler' => ['Admin\\Auth', 'logout']
            ],
            //firm

            [
                'method'  => 'GET',
                'pattern' => '/admin_api/firm/{firm_id:\d+}/attach_user/{user_id:\d+}',
                'handler' => ['Admin\\Firm', 'attachUser']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/admin_api/firm/counter',
                'handler' => ['Admin\\Firm', 'firmCounter']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/admin_api/firm/{type}/{page:\d+}',
                'handler' => ['Admin\\Firm', 'getFirmsList']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/admin_api/firm/{id:\d+}',
                'handler' => ['Admin\\Firm', 'getFirm']
            ],
            [
                'method'  => 'PUT',
                'pattern' => '/admin_api/firm/{id:\d+}',
                'handler' => ['Admin\\Firm', 'updateFirm']
            ],
            [
                'method'  => 'DELETE',
                'pattern' => '/admin_api/firm/{id:\d+}',
                'handler' => ['Admin\\Firm', 'deleteFirm']
            ],

            //region
            [
                'method'  => 'GET',
                'pattern' => '/admin_api/getRegions',
                'handler' => ['Admin\\Region', 'getRegions']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/admin_api/regions',
                'handler' => ['Admin\\Region', 'getRegions']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/admin_api/getCities/{id:\d+}',
                'handler' => ['Admin\\Region', 'getCitiesByRegion']
            ],

            //review
            [
                'method'  => 'GET',
                'pattern' => '/admin_api/review/{type}/{page:\d+}',
                'handler' => ['Admin\\Review', 'getReviewsList']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/admin_api/review/counter',
                'handler' => ['Admin\\Review', 'reviewCounter']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/admin_api/review/{id:\d+}',
                'handler' => ['Admin\\Review', 'getReview']
            ],
            [
                'method'  => 'PUT',
                'pattern' => '/admin_api/review/{id:\d+}',
                'handler' => ['Admin\\Review', 'saveReview']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/admin_api/rw/{id:\d+}',
                'handler' => ['Admin\\Review', 'saveReviewTest']
            ],
            [
                'method'  => 'DELETE',
                'pattern' => '/admin_api/review/{id:\d+}',
                'handler' => ['Admin\\Review', 'deleteReview']
            ],

            //user
            [
                'method'  => 'POST',
                'pattern' => '/admin_api/users/add',
                'handler' => ['Admin\\User', 'addUser']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/admin_api/users/{type}/{page:\d+}',
                'handler' => ['Admin\\User', 'getList']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/admin_api/users/search/{name}',
                'handler' => ['Admin\\User', 'searchUser']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/admin_api/user/{id:\d+}',
                'handler' => ['Admin\\User', 'getUser']
            ],
            [
                'method'  => 'PUT',
                'pattern' => '/admin_api/user/{id:\d+}',
                'handler' => ['Admin\\User', 'updateUser']
            ],
            [
                'method'  => 'DELETE',
                'pattern' => '/admin_api/user/{id:\d+}',
                'handler' => ['Admin\\User', 'removeUser']
            ],
            [
                'method'  => 'DELETE',
                'pattern' => '/admin_api/unbind_firm/{id:\d+}',
                'handler' => ['Admin\\User', 'unbindFirm']
            ],

            //city
            [
                'method'  => 'GET',
                'pattern' => '/admin_api/cities/{type}/{page:\d+}',
                'handler' => ['Admin\\City', 'getList']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/admin_api/city/{id:\d+}',
                'handler' => ['Admin\\City', 'getCity']
            ],
            [
                'method'  => 'PUT',
                'pattern' => '/admin_api/city/{id:\d+}',
                'handler' => ['Admin\\City', 'updateCity']
            ],
            [
                'method'  => 'POST',
                'pattern' => '/admin_api/city/add',
                'handler' => ['Admin\\City', 'addCity']
            ],
            [
                'method'  => 'DELETE',
                'pattern' => '/admin_api/city/{id:\d+}',
                'handler' => ['Admin\\City', 'deleteCity']
            ],

            //main routes /профиль/компании
            ['method'  => 'GET',
             'pattern' => '/профиль/настройки',
             'handler' => ['User', 'settings'],
            ],
            ['method'  => 'POST',
             'pattern' => '/профиль/настройки',
             'handler' => ['User', 'settingsSubmit'],
            ],
            [
                'method'  => 'GET',
                'pattern' => '/профиль/это-моя-компания/{id:\d+}',
                'handler' => ['Firm', 'myCompany']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/show_site/{id:\d+}',
                'handler' => ['Firm', 'showSite']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/профиль/компании/удалить/{id:\d+}',
                'handler' => ['User', 'delete_firm_prompt']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/профиль/компании/удалить/{id:\d+}/да',
                'handler' => ['User', 'delete_firm']
            ],
            [
                'method'  => 'POST',
                'pattern' => '/профиль/это-моя-компания/{id:\d+}',
                'handler' => ['Firm', 'myCompanyHandler']
            ],

            [
                'method'  => 'GET',
                'pattern' => '/ошибка-на-странице/{id:\d+}',
                'handler' => ['OneClick', 'form']
            ],
            [
                'method'  => 'POST',
                'pattern' => '/ошибка-на-странице/{id:\d+}',
                'handler' => ['OneClick', 'form_handler']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/attach-user/{firm_id:\d+}/{user_id:\d+}',
                'handler' => ['OneClick', 'attach_user']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/one-click/{id:\d+}/submit',
                'handler' => ['OneClick', 'make_changes']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/поиск-по-сайту',
                'handler' => ['Index', 'search']
            ],

            //НОВЫЙ ПОИСК
            [
                'method'  => 'GET',
                'pattern' => '/{city}/поиск/{query}',
                'handler' => ['Search\\Search', 'index']
            ],
            [
                'method'  => 'POST',
                'pattern' => '/search/firm/{id:\d+}',
                'handler' => ['Search\\Search', 'getFirmById']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/search/ajax/{query}',
                'handler' => ['Search\\Search', 'ajax']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/search/markers/{query}',
                'handler' => ['Search\\Search', 'getMarkers']
            ],
            [
                'method'  => 'POST',
                'pattern' => '/search/live',
                'handler' => ['Search\\Search', 'liveSearch']
            ],

            [
                'method'  => 'GET',
                'pattern' => '/{city}/найти/{query}',
                'handler' => ['Search\\Search', 'front']
            ],
            
            [
                'method'  => 'GET',
                'pattern' => '/реклама',
                'handler' => ['Pages', 'showAds'],
            ],
            [
                'method'  => 'GET',
                'pattern' => '/написать-нам',
                'handler' => ['Pages', 'feedback']
            ],
            [
                'method'  => 'POST',
                'pattern' => '/написать-нам',
                'handler' => ['Pages', 'feedback_handler']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/добавить-компанию',
                'handler' => ['User', 'add_firm']
            ],
            [
                'method'  => 'POST',
                'pattern' => '/добавить-компанию',
                'handler' => ['User', 'add_firm_handler']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/профиль/вход',
                'handler' => ['User', 'login']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/профиль',
                'handler' => ['User', 'user_profile']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/профиль/компании',
                'handler' => ['User', 'user_firms']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/профиль/компании/стр/{page:\d+}',
                'handler' => ['User', 'user_firms']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/профиль/выход',
                'handler' => ['User', 'user_logout']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/firm/edit/{id:\d+}',
                'handler' => ['User', 'firm_edit']
            ],
            [
                'method'  => 'POST',
                'pattern' => '/firm/edit/{id:\d+}',
                'handler' => ['User', 'firm_edit_handler']
            ],
            [
                'method'  => 'POST',
                'pattern' => '/профиль/вход',
                'handler' => ['User', 'login_post']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/пользовательское-соглашение',
                'handler' => ['Pages', 'user_agreement']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/правила-публикации',
                'handler' => ['Pages', 'pub_rules']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/формат/{type}/{firm:\d+}',
                'handler' => ['Firm', 'format']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/qrcode/{firm:\d+}',
                'handler' => ['Firm', 'qrcode']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/ajax/regionsList',
                'handler' => ['Index', 'regionsList']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/ajax/adblock',
                'handler' => ['Index', 'adblock']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/ajax/adblock_mobile',
                'handler' => ['Index', 'adblock_mobile']
            ],


            [
                'method'  => 'GET',
                'pattern' => '/ajax/add_homescreen',
                'handler' => ['Index', 'add_homescreen']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/ajax/add_homescreen_mobile',
                'handler' => ['Index', 'add_homescreen_mobile']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/ajax/add_homescreen_ipad',
                'handler' => ['Index', 'add_homescreen_ipad']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/ajax/add_homescreen_iphone',
                'handler' => ['Index', 'add_homescreen_iphone']
            ],

            [
                'method'  => 'POST',
                'pattern' => '/ajax/firm/review/{firm:\d+}',
                'handler' => ['Firm', 'review']
            ],
            [
                'method'  => 'POST',
                'pattern' => '/ajax/firm/upload/{firm:\d+}',
                'handler' => ['Firm', 'upload_photo']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/asset/thumb/{width}/{url:.+}',
                'handler' => ['Images', 'thumb']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/',
                'handler' => ['Index', 'main']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/system/cron/{key}',
                'handler' => ['System', 'cron']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/{city}/{group}[/стр/{page:\d+}]',
                'handler' => ['Rubric', 'main']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/{city}/{group}/{sort:по-рейтингу|по-алфавиту}[/стр/{page:\d+}]',
                'handler' => ['Rubric', 'withSort']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/{city}/ключевое-слово/{group}[/стр/{page:\d+}]',
                'handler' => ['Rubric', 'mainKey']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/{city}/ключевое-слово/{group}/{sort:по-рейтингу|по-алфавиту}[/стр/{page:\d+}]',
                'handler' => ['Rubric', 'keyWithSort']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/профиль/{user:\d+}',
                'handler' => ['Firm', 'show']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/{region}',
                'handler' => ['Region', 'dispatch']
            ],
            [
                'method'  => 'GET',
                'pattern' => '/firm/show/{id:\d+}',
                'handler' => ['Firm', 'show']
            ],
        ];
}
