<?php

declare(strict_types=1);

namespace App\Container\Infrastructure\Repository;

use Doctrine\DBAL\Connection;
use Interop\Container\ContainerInterface;

final class SqlRepositoryFactory
{
    /**
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, string $name)
    {
        $className = $this->addSqlToClassName($name);

        return new $className(
            $container->get(Connection::class)
        );
    }

    private function addSqlToClassName($name)
    {
        $exploded = explode('\\', $name);

        $interfaceName = array_pop($exploded);

        array_push($exploded, 'Sql' . $interfaceName);

        return implode('\\', $exploded);
    }
}
