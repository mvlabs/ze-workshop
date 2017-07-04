<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\User;
use Doctrine\DBAL\Connection;

final class SqlUsers implements Users
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function add(User $user): void
    {
        $statement = $this->connection->executeQuery(
            'INSERT INTO users (id, name, surname, admin) '.
            'VALUES (:id, :name, :surname, :admin)',
            [
                'id' => $user->id(),
                'name' => $user->name(),
                'surname' => $user->surname(),
                'admin' => $user->isAdministrator()
            ]
        );

        $statement->execute();
    }
}
