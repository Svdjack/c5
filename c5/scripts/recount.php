<?php
//phpinfo();
//die();
// Worthless MVC Engine v0.0.1
// by Bapewka 2015
$timer = microtime(true);

$_SERVER['HTTP_HOST'] = 'xn--80adsqinks2h.xn--p1ai';

define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('APP_PATH', ROOT_PATH . 'application' . DIRECTORY_SEPARATOR);
require_once(ROOT_PATH . "/vendor/autoload.php"); // composer
require_once(APP_PATH . "/propel/conf/config.php"); // setup Propel
require APP_PATH . 'Core' . DIRECTORY_SEPARATOR . 'core.php';

use wMVC\Core\Application;
//
//new Application();

//print "<!-- Page generated in " . (microtime(true) - $timer) . "s -->\n";



$regions = \PropelModel\RegionQuery::create()->filterByArea(null)->find();

foreach($regions as $region){
    $count = 0;
    foreach(\PropelModel\RegionQuery::create()->findByArea($region->getId()) as $city){
        $city_count = \PropelModel\FirmQuery::create()->filterByCityId($city->getId())->count();
        $city->setCount($city_count);
        $city->save();
        $count += $city->getCount();
        print $city->getName().PHP_EOL;
    }
    $region->setCount($count);
    $region->save();
}