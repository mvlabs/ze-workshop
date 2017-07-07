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
            '   u.username, ' .
            '   u.password, ' .
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
            'INSERT INTO users (id, username, password, admin) '.
            'VALUES (:id, :username, :password, :admin) '.
            'ON CONFLICT DO NOTHING',
            [
                'id' => (string) $user->id(),
                'username' => $user->username(),
                'password' => $user->password(),
                'admin' => $user->isAdministrator() ? 'true' : 'false'
            ]
        );
    }

    public function createUser(
        string $id,
        string $username,
        string $password,
        bool $isAdministrator
    ) {
        return User::fromNativeData(
            $id,
            $username,
            $password,
            $isAdministrator
        );
    }
}
