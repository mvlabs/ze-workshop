<?php

declare(strict_types=1);

namespace AppTest\Infrastructure\Repository;

use App\Domain\Entity\User;
use App\Domain\Value\UserId;
use App\Infrastructure\Repository\SqlUsers;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;
use Mockery;
use PHPUnit\Framework\TestCase;

final class SqlUsersTest extends TestCase
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var SqlUsers
     */
    private $repository;

    public function setUp(): void
    {
        $this->connection = Mockery::mock(Connection::class);
        $this->repository = new SqlUsers($this->connection);
    }

    public function testAll(): void
    {
        $statement = Mockery::mock(Statement::class);
        $statement->shouldReceive('fetchAll')->with(
            \PDO::FETCH_FUNC,
            [SqlUsers::class, 'createUser']
        )->andReturn([]);

        $this->connection->shouldReceive('executeQuery')->with(
            'SELECT ' .
            '   u.id, ' .
            '   u.username, ' .
            '   u.password, ' .
            '   u.admin ' .
            'FROM users u'
        )->andReturn($statement);

        self::assertSame([], $this->repository->all());
    }

    public function testAddUser(): void
    {
        $user = User::new('gigi', 'zucon');

        $this->connection->shouldReceive('executeUpdate')->with(
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

        $this->repository->add($user);
    }

    public function testFindByIdWithNoResult(): void
    {
        $statement = Mockery::mock(Statement::class);
        $statement->shouldReceive('fetch')->with(
            \PDO::FETCH_ASSOC
        )->andReturn(null);

        $userId = UserId::new();

        $this->connection->shouldReceive('executeQuery')->with(
            'SELECT ' .
            '   u.id, ' .
            '   u.username, ' .
            '   u.password, ' .
            '   u.admin ' .
            'FROM users u ' .
            'WHERE id = :id',
            [
                'id' => (string) $userId
            ]
        )->andReturn($statement);

        self::assertNull($this->repository->findById($userId));
    }

    public function testFindByIdWithResult(): void
    {
        $user = User::new('gigi', 'zucon');

        $statement = Mockery::mock(Statement::class);
        $statement->shouldReceive('fetch')->with(
            \PDO::FETCH_ASSOC
        )->andReturn([
            'id' => (string) $user->id(),
            'username' => $user->username(),
            'password' => $user->password(),
            'admin' => $user->isAdministrator()
        ]);

        $this->connection->shouldReceive('executeQuery')->with(
            'SELECT ' .
            '   u.id, ' .
            '   u.username, ' .
            '   u.password, ' .
            '   u.admin ' .
            'FROM users u ' .
            'WHERE id = :id',
            [
                'id' => (string) $user->id()
            ]
        )->andReturn($statement);

        self::assertEquals($user, $this->repository->findById($user->id()));
    }

    public function testFindByUsernameWithNoResult(): void
    {
        $statement = Mockery::mock(Statement::class);
        $statement->shouldReceive('fetch')->with(
            \PDO::FETCH_ASSOC
        )->andReturn(null);

        $username = 'gigi';

        $this->connection->shouldReceive('executeQuery')->with(
            'SELECT ' .
            '   u.id, ' .
            '   u.username, ' .
            '   u.password, ' .
            '   u.admin ' .
            'FROM users u ' .
            'WHERE username = :username',
            [
                'username' => $username
            ]
        )->andReturn($statement);

        self::assertNull($this->repository->findByUsername($username));
    }

    public function testFindByUsernameWithResult(): void
    {
        $user = User::new('gigi', 'zucon');

        $statement = Mockery::mock(Statement::class);
        $statement->shouldReceive('fetch')->with(
            \PDO::FETCH_ASSOC
        )->andReturn([
            'id' => (string) $user->id(),
            'username' => $user->username(),
            'password' => $user->password(),
            'admin' => $user->isAdministrator()
        ]);

        $this->connection->shouldReceive('executeQuery')->with(
            'SELECT ' .
            '   u.id, ' .
            '   u.username, ' .
            '   u.password, ' .
            '   u.admin ' .
            'FROM users u ' .
            'WHERE username = :username',
            [
                'username' => $user->username()
            ]
        )->andReturn($statement);

        self::assertEquals($user, $this->repository->findByUsername($user->username()));
    }

    protected function assertPostConditions(): void
    {
        $container = Mockery::getContainer();
        if (null !== $container) {
            $count = $container->mockery_getExpectationCount();
            $this->addToAssertionCount($count);
        }

        \Mockery::close();
    }
}
