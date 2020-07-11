<?php
define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR);
define('APP_PATH', ROOT_PATH . 'application' . DIRECTORY_SEPARATOR);
require_once(ROOT_PATH . "/vendor/autoload.php"); // composer
require_once(APP_PATH . "/propel/conf/config.php"); // setup Propel
require APP_PATH . 'Core' . DIRECTORY_SEPARATOR . 'core.php';

use wMVC\Components\Myredis;
use wMVC\Core\abstractController;

//
$single_server = abstractController::getRedisConf();
$redis = new Myredis($single_server, array('prefix' => 'c5:'));
$redis->select($single_server['database']);
$redis->flushdb();
echo 'Redis flush' . "\n";
