<?php

declare(strict_types=1);

namespace AppTest\Infrastructure\Repository;

use App\Domain\Value\ChocolateId;
use App\Infrastructure\Repository\SqlChocolates;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;
use Mockery;
use PHPUnit\Framework\TestCase;

final class SqlChocolatesTest extends TestCase
{
    private $connection;

    /**
     * @var SqlChocolates
     */
    private $repository;

    public function setUp(): void
    {
        $this->connection = Mockery::mock(Connection::class);
        $this->repository = new SqlChocolates($this->connection);
    }

    public function testAll(): void
    {
        $statement = Mockery::mock(Statement::class);
        $statement->shouldReceive('fetchAll')->with(
            \PDO::FETCH_FUNC,
            [SqlChocolates::class, 'createChocolate']
        )->andReturn([]);

        $this->connection->shouldReceive('executeQuery')->with(
            'SELECT ' .
            '   c.id, ' .
            '   p.name, ' .
            '   p.street, ' .
            '   p.street_number, ' .
            '   p.zip_code, ' .
            '   p.city, ' .
            '   p.region, ' .
            '   p.country, ' .
            '   c.description, ' .
            '   c.cacao_percentage, ' .
            '   c.wrapper_type, ' .
            '   c.quantity, ' .
            '   array_agg(ARRAY[ch.status, u.id, u.name, u.surname, cast(u.admin as varchar), cast(ch.date_time as varchar)]) ' .
            'FROM chocolates c ' .
            'JOIN producers p ON p.id = c.producer_id ' .
            'JOIN chocolates_history ch ON ch.chocolate_id = c.id ' .
            'JOIN users u ON u.id = ch.user_id ' .
            'GROUP BY c.id, p.id'
        )->andReturn($statement);

        self::assertSame([], $this->repository->all());
    }

    public function testFindByIdWithNoResult(): void
    {
        $statement = Mockery::mock(Statement::class);
        $statement->shouldReceive('fetch')->with(\PDO::FETCH_ASSOC)->andReturn(null);

        $chocolateId = ChocolateId::new();

        $this->connection->shouldReceive('executeQuery')->with(
            'SELECT ' .
            '   c.id, ' .
            '   p.name, ' .
            '   p.street, ' .
            '   p.street_number, ' .
            '   p.zip_code, ' .
            '   p.city, ' .
            '   p.region, ' .
            '   p.country, ' .
            '   c.description, ' .
            '   c.cacao_percentage, ' .
            '   c.wrapper_type, ' .
            '   c.quantity, ' .
            '   array_agg(ARRAY[ch.status, u.id, u.name, u.surname, cast(u.admin as varchar), cast(ch.date_time as varchar)]) AS history ' .
            'FROM chocolates c ' .
            'JOIN producers p ON p.id = c.producer_id ' .
            'JOIN chocolates_history ch ON ch.chocolate_id = c.id ' .
            'JOIN users u ON u.id = ch.user_id ' .
            'WHERE c.id = :id',
            [
                'id' => (string) $chocolateId
            ]
        )->andReturn($statement);

        self::assertNull($this->repository->findById($chocolateId));
    }

    protected function assertPostConditions(): void
    {
        $container = Mockery::getContainer();
        if ($container != null) {
            $count = $container->mockery_getExpectationCount();
            $this->addToAssertionCount($count);
        }

        \Mockery::close();
    }
}
