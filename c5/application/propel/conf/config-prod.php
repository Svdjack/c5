<?php
$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
$serviceContainer->checkVersion('2.0.0-dev');
$serviceContainer->setAdapterClass('default', 'mysql');
$manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
$manager->setConfiguration(array(
    'classname' => 'Propel\\Runtime\\Connection\\ConnectionWrapper',
    'dsn' => 'mysql:host=localhost;dbname=tvoyafirma_new;charset=utf8',
    'user' => 'tvoyafirma',
    'password' => 'Gk`_-LbTLpRyp@5',
    'attributes' =>
    array(
        'ATTR_EMULATE_PREPARES' => false,
    ),
));
$manager->setName('default');
$serviceContainer->setConnectionManager('default', $manager);
$serviceContainer->setDefaultDatasource('default');
