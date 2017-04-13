<?php

declare(strict_types=1);

namespace App\App\Infrastructure\Repository;

use App\Domain\Entity\Chocolate;
use Doctrine\DBAL\Connection;

final class Chocolates
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function all(): array
    {
        $allChocolatesStatement = $this->connection->executeQuery(
            'SELECT * FROM chocolates'
        );

        return $allChocolatesStatement->fetchAll(
            \PDO::FETCH_FUNC,
            [Chocolate::class, 'fromNativeData']
        );
    }
}
