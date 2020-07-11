<?php
/* Auto deploy */
!defined('AUTODEPLOY') && define('AUTODEPLOY', true);
if (\file_exists(__DIR__ . '/autodeploy.php')) {
    include_once __DIR__ . '/autodeploy.php';
}

//phpinfo();
//die();
// Worthless MVC Engine v0.0.1
// by Bapewka 2015
$timer = microtime(true);
define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('APP_PATH', ROOT_PATH . 'application' . DIRECTORY_SEPARATOR);
require_once(ROOT_PATH . "/vendor/autoload.php"); // composer
require_once(APP_PATH . "/propel/conf/config.php"); // setup Propel
require APP_PATH . 'Core' . DIRECTORY_SEPARATOR . 'core.php';

use wMVC\Core\Application;
if (DEBUG) {
    //phpinfo();
}
new Application();

//print "<!-- Page generated in " . (microtime(true) - $timer) . "s -->\n";
