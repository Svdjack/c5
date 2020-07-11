<?php

$_SERVER['HTTP_HOST'] = 'ora';
//phpinfo();
//die();
// Worthless MVC Engine v0.0.1
// by Bapewka 2015
$timer = microtime(true);

require_once __DIR__.'/aliases.php';

define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('APP_PATH', ROOT_PATH . 'application' . DIRECTORY_SEPARATOR);
require_once(ROOT_PATH . "/vendor/autoload.php"); // composer
require_once(APP_PATH . "/propel/conf/config.php"); // setup Propel
require APP_PATH . 'Core' . DIRECTORY_SEPARATOR . 'core.php';

use wMVC\Core\Application;

//
//new Application();

//print "<!-- Page generated in " . (microtime(true) - $timer) . "s -->\n";


$aliases = array_flip($aliases);

$groups = \PropelModel\GroupQuery::create()->find();


$count = 0;
foreach ($groups as $group) {
    if(isset($aliases[$group->getName()])){
        $group->setOriginal($aliases[$group->getName()]);
        $group->save();
        $count++;
    }
}

print PHP_EOL;
print $count;
