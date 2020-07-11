<?php

require_once __DIR__."/db.php";

$timer = microtime(true);

define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('APP_PATH', ROOT_PATH . 'application' . DIRECTORY_SEPARATOR);
require_once(ROOT_PATH . "/vendor/autoload.php"); // composer
require_once(APP_PATH . "/propel/conf/config.php"); // setup Propel
require APP_PATH . 'Core' . DIRECTORY_SEPARATOR . 'core.php';

use PropelModel\FirmGroup;
use PropelModel\GroupQuery;
use PropelModel\LegalInfo;
use PropelModel\RegionQuery;
use PropelModel\UrlAliases;
use PropelModel\UrlAliasesQuery;
use wMVC\Core\Application;
use wMVC\Entity\Lang;

//
//new Application();

//print "<!-- Page generated in " . (microtime(true) - $timer) . "s -->\n";

$_SERVER['HTTP_HOST'] = 'localhost';




$districts = \PropelModel\DistrictQuery::create()->find();


foreach($districts as $district){
    $shitty_shit = $district->getName();
    $values = morph($shitty_shit);
    $district->setData(serialize($values));
    $district->save();
}



function morph($word){
    $url = urlencode($word);
    $url = "http://api.morpher.ru/WebService.asmx/GetXml?s=" . $url . "&username=kolombo&password=290688";
    $content = file_get_contents($url);
    $result = array($word);
    $cases = array('Р', 'Д', 'В', 'Т', 'П');
    foreach ($cases AS $case) {
        preg_match('/<' . $case . '>(.*?)\</ui', $content, $morph_word);
        if (array_key_exists(1, $morph_word)) {
            $result[] = $morph_word[1];
        } else {
            $result[] = $word;
        }
    }
    return $result;
}