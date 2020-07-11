<?php

require_once __DIR__."/db.php";

require_once __DIR__."/aliases.php";

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

$days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

query('USE tvoyafirma_new');

//удаляем сначала старье...

$query_ids = query('SELECT id from firm where id not in (select firm_id from firm_user) and created = 0');

$ids = [];

foreach($query_ids as $item){
    $ids[] = $item['id'];
}

print count($ids);

GroupQuery::create()->find();

$count = 0;
foreach($ids as $item){

    print $count++;
    $firm = \PropelModel\FirmQuery::create()->findPk($item);

    print 'current: ';
    print $firm->getAlias();
    print PHP_EOL;

    print 'new: ';

    $city = $firm->getRegion()->getUrl();
//    print PHP_EOL."MAINCAT IS ".$firm->getMainCategory().PHP_EOL;
    if(!$firm->getMainCategory()){
        continue;
    }
    $main_cat = GroupQuery::create()->findPk($firm->getMainCategory())->getUrl();
    $url = Lang::toUrl($firm->getName());

    $alias = "/{$city}/{$main_cat}/{$url}";

    if (UrlAliasesQuery::create()->filterByAlias($alias)->count()) {
        $alias .= '-' . $firm->getId();
    }

    print $alias;

    $alias_obj = UrlAliasesQuery::create()->findOneBySource('/firm/show/'.$firm->getId());
    $alias_obj->setAlias($alias);
    $alias_obj->save();


    print PHP_EOL;
    print PHP_EOL;

    $alias_obj = null;
    $firm = null;
}


die();

$city = $region->getUrl();
$main_cat = GroupQuery::create()->findPk($new_firm->getMainCategory())->getUrl();
$url = Lang::toUrl($firm['name']);

$alias = "/{$city}/{$main_cat}/{$url}";

if (UrlAliasesQuery::create()->filterByAlias($alias)->count()) {
    $alias .= '-' . $new_firm->getId();
}

$alias_obj = new UrlAliases();
$alias_obj->setSource('/firm/show/' . $new_firm->getId());
$alias_obj->setAlias($alias);
$alias_obj->save();