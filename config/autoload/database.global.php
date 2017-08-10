<?php

declare(strict_types=1);

return [
    'doctrine' => [
        'connection' => [
            'default' => [
                'driverClass' => \Doctrine\DBAL\Driver\PDOPgSql\Driver::class,
                'host' => 'localhost',
                'port' => '5432',
                'user' => 'websc',
                'password' => 'websc',
                'dbname' => 'ze-workshop'
            ],
        ],
    ],
];
