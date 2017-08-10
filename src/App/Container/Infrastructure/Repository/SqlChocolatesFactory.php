<?php

declare(strict_types=1);

namespace App\Container\Infrastructure\Repository;

use App\Infrastructure\Repository\SqlChocolates;
use Doctrine\DBAL\Connection;
use Interop\Container\ContainerInterface;

final class SqlChocolatesFactory
{
    public function __invoke(ContainerInterface $container): SqlChocolates
    {
        return new SqlChocolates(
            $container->get(Connection::class)
        );
    }
}
