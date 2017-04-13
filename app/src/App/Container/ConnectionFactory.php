<?php

declare(strict_types=1);

namespace App\Container;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Psr\Container\ContainerInterface;

final class ConnectionFactory
{
    public function __invoke(ContainerInterface $container): Connection
    {
        $config = $container->get('config')['doctrine']['connection']['default'];

        return DriverManager::getConnection($config);
    }
}
