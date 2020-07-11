<?php

return [
    'propel' => [
        'database'  => [
            'connections' => [
                'default' => [
                    'adapter'    => 'mysql',
                    //                    'classname'  => 'Propel\Runtime\Connection\DebugPDO',
                    'classname'  => 'Propel\Runtime\Connection\ConnectionWrapper',
                    'dsn'        => 'mysql:host=localhost;dbname=tvoyafirma_new;charset=utf8',
                    'user'       => 'tvoyafirma',
                    'password'   => 'Gk`_-LbTLpRyp@5',
                    'attributes' => []
                ]
            ]
        ],
        'runtime'   => [
            'defaultConnection' => 'default',
            'connections'       => ['default']
        ],
        'generator' => [
            'defaultConnection' => 'default',
            'connections'       => ['default']
        ],
        'paths'     => [
            'projectDir'   => 'current_path',
            'phpDir'       => 'application/propel/classes',
            'phpConfDir'   => 'application/propel/conf',
            'migrationDir' => 'application/propel/migrations',
            'sqlDir'       => 'application/propel/sql'
        ]
    ]
];
