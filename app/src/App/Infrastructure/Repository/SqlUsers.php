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

    /**
     * @return User[]
     */
    public function all(): array
    {
        $allChocolatesStatement = $this->connection->executeQuery(
            'SELECT ' .
            '   u.id, ' .
            '   u.name, ' .
            '   u.surname, ' .
            '   u.admin ' .
            'FROM users u'
        );

        return $allChocolatesStatement->fetchAll(
            \PDO::FETCH_FUNC,
            [self::class, 'createUser']
        );
    }

    public function add(User $user): void
    {
        $this->connection->executeUpdate(
            'INSERT INTO users (id, name, surname, admin) '.
            'VALUES (:id, :name, :surname, :admin) '.
            'ON CONFLICT DO NOTHING',
            [
                'id' => (string) $user->id(),
                'name' => $user->name(),
                'surname' => $user->surname(),
                'admin' => $user->isAdministrator() ? 'true' : 'false'
            ]
        );
    }

    public function createUser(
        string $id,
        string $name,
        string $surname,
        bool $isAdministrator
    ) {
        return User::fromNativeData(
            $id,
            $name,
            $surname,
            $isAdministrator
        );
    }
}
