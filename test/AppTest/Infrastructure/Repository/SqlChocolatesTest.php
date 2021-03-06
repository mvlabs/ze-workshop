<?php

declare(strict_types=1);

namespace AppTest\Infrastructure\Repository;

use App\Domain\Entity\Chocolate;
use App\Domain\Entity\User;
use App\Domain\Service\Exception\InvalidStatusTransitionException;
use App\Domain\Value\Address;
use App\Domain\Value\ChocolateId;
use App\Domain\Value\Country;
use App\Domain\Value\Percentage;
use App\Domain\Value\Producer;
use App\Domain\Value\Quantity;
use App\Domain\Value\Status;
use App\Domain\Value\UserId;
use App\Domain\Value\WrapperType;
use App\Infrastructure\Repository\SqlChocolates;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;
use Mockery;
use PHPUnit\Framework\TestCase;

final class SqlChocolatesTest extends TestCase
{
    /**
     * @var Connection
     */
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
            '   array_agg(ARRAY[ch.status, u.id, u.username, u.password, cast(u.admin as varchar), cast(ch.date_time as varchar)]) ' .
            'FROM chocolates c ' .
            'JOIN producers p ON p.id = c.producer_id ' .
            'JOIN chocolates_history ch ON ch.chocolate_id = c.id ' .
            'JOIN users u ON u.id = ch.user_id  ' .
            'GROUP BY c.id, p.id',
            []
        )->andReturn($statement);

        self::assertSame([], $this->repository->all());
    }

    public function testAllWithFilters(): void
    {
        $statement = Mockery::mock(Statement::class);
        $statement->shouldReceive('fetchAll')->with(
            \PDO::FETCH_FUNC,
            [SqlChocolates::class, 'createChocolate']
        )->andReturn([]);

        $filters = [
            'description' => 'very good',
            'not-a-key' => 'not a value'
        ];

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
            '   array_agg(ARRAY[ch.status, u.id, u.username, u.password, cast(u.admin as varchar), cast(ch.date_time as varchar)]) ' .
            'FROM chocolates c ' .
            'JOIN producers p ON p.id = c.producer_id ' .
            'JOIN chocolates_history ch ON ch.chocolate_id = c.id ' .
            'JOIN users u ON u.id = ch.user_id ' .
            'WHERE c.description = :description ' .
            'GROUP BY c.id, p.id',
            ['description' => 'very good']
        )->andReturn($statement);

        self::assertSame([], $this->repository->all($filters));
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
            '   array_agg(ARRAY[ch.status, u.id, u.username, u.password, cast(u.admin as varchar), cast(ch.date_time as varchar)]) AS history ' .
            'FROM chocolates c ' .
            'JOIN producers p ON p.id = c.producer_id ' .
            'JOIN chocolates_history ch ON ch.chocolate_id = c.id ' .
            'JOIN users u ON u.id = ch.user_id ' .
            'WHERE c.id = :id ' .
            'GROUP BY c.id, p.id ',
            [
                'id' => (string) $chocolateId
            ]
        )->andReturn($statement);

        self::assertNull($this->repository->findById($chocolateId));
    }

    public function testFindByIdWithResult(): void
    {
        $chocolateId = ChocolateId::new();
        $userId = (string) UserId::new();
        $date = date_create_immutable()->format('Y-m-d H:i:s');

        $chocolateData = [
            'id' => (string) $chocolateId,
            'name' => 'bittersweet',
            'street' => 'via Diqua',
            'street_number' => '1A',
            'zip_code' => 'AB123',
            'city' => 'Treviso',
            'region' => 'TV',
            'country' => 'IT',
            'description' => 'dark',
            'cacao_percentage' => 77,
            'wrapper_type' => 'box',
            'quantity' => 100,
            'history' => sprintf(
                '{%s,%s,%s,%s,%s,%s}',
                Status::SUBMITTED,
                $userId,
                'gigi',
                'Zucon',
                'true',
                $date
            )
        ];

        $statement = Mockery::mock(Statement::class);
        $statement->shouldReceive('fetch')->with(\PDO::FETCH_ASSOC)->andReturn($chocolateData);

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
            '   array_agg(ARRAY[ch.status, u.id, u.username, u.password, cast(u.admin as varchar), cast(ch.date_time as varchar)]) AS history ' .
            'FROM chocolates c ' .
            'JOIN producers p ON p.id = c.producer_id ' .
            'JOIN chocolates_history ch ON ch.chocolate_id = c.id ' .
            'JOIN users u ON u.id = ch.user_id ' .
            'WHERE c.id = :id ' .
            'GROUP BY c.id, p.id ',
            [
                'id' => (string) $chocolateId
            ]
        )->andReturn($statement);

        $chocolate = Chocolate::fromNativeData(
            $chocolateData['id'],
            $chocolateData['name'],
            $chocolateData['street'],
            $chocolateData['street_number'],
            $chocolateData['zip_code'],
            $chocolateData['city'],
            $chocolateData['region'],
            $chocolateData['country'],
            $chocolateData['description'],
            $chocolateData['cacao_percentage'],
            $chocolateData['wrapper_type'],
            $chocolateData['quantity'],
            [
                [
                    'status' => Status::SUBMITTED,
                    'user_id' => $userId,
                    'user_username' => 'gigi',
                    'user_password' => 'Zucon',
                    'user_is_administrator' => true,
                    'date_time' => date_create_immutable($date)

                ]
            ]

        );
        self::assertEquals($chocolate, $this->repository->findById($chocolateId));
    }

    public function testAddChocolateWithExistingProducer(): void
    {
        $this->connection->shouldReceive('beginTransaction');

        $producerId = 37;
        $producerStatement = Mockery::mock(Statement::class);
        $producerStatement->shouldReceive('fetchColumn')->andReturn($producerId);

        $producer = Producer::fromNameAndAddress(
            'bittersweet',
            Address::fromStreetNumberZipCodeCityRegionCountry(
                'via Diqua',
                '1A',
                'AB123',
                'Treviso',
                'TV',
                Country::fromStringCode('IT')
            )
        );
        $this->connection->shouldReceive('executeQuery')->with(
            'SELECT id FROM producers WHERE name = :name',
            [
                'name' => $producer->name()
            ]
        )->andReturn($producerStatement);

        $statement = Mockery::mock(Statement::class);
        $statement->shouldReceive('execute');

        $chocolate = Chocolate::submit(
            ChocolateId::new(),
            $producer,
            'dark',
            Percentage::integer(77),
            WrapperType::get(WrapperType::BOX),
            Quantity::grams(100),
            User::new('gigi', 'Zucon')
        );

        $this->connection->shouldReceive('executeQuery')->with(
            'INSERT INTO chocolates (
                id,
                producer_id,
                description,
                cacao_percentage,
                wrapper_type,
                quantity
            ) VALUES (
                :chocolate_id,
                :producer_id,
                :description,
                :cacao_percentage,
                :wrapper_type,
                :quantity
            )',
            [
                'chocolate_id' => (string) $chocolate->id(),
                'producer_id' => $producerId,
                'description' => $chocolate->description(),
                'cacao_percentage' => $chocolate->cacaoPercentage()->toInt(),
                'wrapper_type' => $chocolate->wrapperType()->getValue(),
                'quantity' => $chocolate->quantity()->toInt()
            ]
        )->andReturn($statement);

        $historyStatement = Mockery::mock(Statement::class);
        $historyStatement->shouldReceive('execute');

        $this->connection->shouldReceive('executeQuery')->with(
            'INSERT INTO chocolates_history (
                chocolate_id,
                status,
                user_id,
                date_time
            ) VALUES (
                :chocolate_id,
                :status,
                :user_id,
                :date_time
            )',
            [
                'chocolate_id' => (string) $chocolate->id(),
                'status' => $chocolate->status()->getValue(),
                'user_id' => $chocolate->lastTransitionUserId(),
                'date_time' => $chocolate->lastTransitionTime()->format('Y-m-d H:i:s')
            ]
        )->andReturn($historyStatement);

        $this->connection->shouldReceive('commit');

        $this->repository->add($chocolate);
    }

    public function testAddChocolateWithNewProducer(): void
    {
        $this->connection->shouldReceive('beginTransaction');

        $producerId = 37;
        $producerStatement = Mockery::mock(Statement::class);
        $producerStatement->shouldReceive('fetchColumn')->andReturn(false);

        $producer = Producer::fromNameAndAddress(
            'bittersweet',
            Address::fromStreetNumberZipCodeCityRegionCountry(
                'via Diqua',
                '1A',
                'AB123',
                'Treviso',
                'TV',
                Country::fromStringCode('IT')
            )
        );
        $this->connection->shouldReceive('executeQuery')->with(
            'SELECT id FROM producers WHERE name = :name',
            [
                'name' => $producer->name()
            ]
        )->andReturn($producerStatement);

        $newProducerStatement = Mockery::mock(Statement::class);
        $newProducerStatement->shouldReceive('fetchColumn')->andReturn(37);

        $this->connection->shouldReceive('executeQuery')->with(
            'INSERT INTO producers (
                name,
                street,
                street_number,
                zip_code,
                city,
                region,
                country
            ) VALUES (
                :name,
                :street,
                :street_number,
                :zip_code,
                :city,
                :region,
                :country
            ) RETURNING id',
            [
                'name' => $producer->name(),
                'street' => $producer->street(),
                'street_number' => $producer->streetNumber(),
                'zip_code' => $producer->zipCode(),
                'city' => $producer->city(),
                'region' => $producer->region(),
                'country' => $producer->countryCode()->getValue()
            ]
        )->andReturn($newProducerStatement);

        $statement = Mockery::mock(Statement::class);
        $statement->shouldReceive('execute');

        $chocolate = Chocolate::submit(
            ChocolateId::new(),
            $producer,
            'dark',
            Percentage::integer(77),
            WrapperType::get(WrapperType::BOX),
            Quantity::grams(100),
            User::new('gigi', 'Zucon')
        );

        $this->connection->shouldReceive('executeQuery')->with(
            'INSERT INTO chocolates (
                id,
                producer_id,
                description,
                cacao_percentage,
                wrapper_type,
                quantity
            ) VALUES (
                :chocolate_id,
                :producer_id,
                :description,
                :cacao_percentage,
                :wrapper_type,
                :quantity
            )',
            [
                'chocolate_id' => (string) $chocolate->id(),
                'producer_id' => $producerId,
                'description' => $chocolate->description(),
                'cacao_percentage' => $chocolate->cacaoPercentage()->toInt(),
                'wrapper_type' => $chocolate->wrapperType()->getValue(),
                'quantity' => $chocolate->quantity()->toInt()
            ]
        )->andReturn($statement);

        $historyStatement = Mockery::mock(Statement::class);
        $historyStatement->shouldReceive('execute');

        $this->connection->shouldReceive('executeQuery')->with(
            'INSERT INTO chocolates_history (
                chocolate_id,
                status,
                user_id,
                date_time
            ) VALUES (
                :chocolate_id,
                :status,
                :user_id,
                :date_time
            )',
            [
                'chocolate_id' => (string) $chocolate->id(),
                'status' => $chocolate->status()->getValue(),
                'user_id' => $chocolate->lastTransitionUserId(),
                'date_time' => $chocolate->lastTransitionTime()->format('Y-m-d H:i:s')
            ]
        )->andReturn($historyStatement);

        $this->connection->shouldReceive('commit');

        $this->repository->add($chocolate);
    }

    public function testApproveChocolate(): void
    {
        $chocolate = Chocolate::submit(
            ChocolateId::new(),
            Producer::fromNameAndAddress(
                'bittersweet',
                Address::fromStreetNumberZipCodeCityRegionCountry(
                    'via Diqua',
                    '1A',
                    'AB123',
                    'Treviso',
                    'TV',
                    Country::fromStringCode('IT')
                )
            ),
            'dark',
            Percentage::integer(77),
            WrapperType::get(WrapperType::BOX),
            Quantity::grams(100),
            User::new('gigi', 'Zucon')
        );
        $chocolate->approve(User::newAdministrator('toni', 'Folpet'));

        $statement = Mockery::mock(Statement::class);
        $statement->shouldReceive('execute');

        $this->connection->shouldReceive('executeQuery')->with(
            'INSERT INTO chocolates_history (
                chocolate_id,
                status,
                user_id,
                date_time
            ) VALUES (
                :chocolate_id,
                :status,
                :user_id,
                :date_time
            )',
            [
                'chocolate_id' => (string) $chocolate->id(),
                'status' => $chocolate->status()->getValue(),
                'user_id' => (string) $chocolate->lastTransitionUserId(),
                'date_time' => $chocolate->lastTransitionTime()->format('Y-m-d H:i:s')
            ]
        )->andReturn($statement);

        $this->repository->approve($chocolate);
    }

    public function testApproveChocolateInWrongState(): void
    {
        $this->expectException(InvalidStatusTransitionException::class);
        $this->expectExceptionMessage('Can not persist the approval of a chocolate which is not in approved status');

        $chocolate = Chocolate::submit(
            ChocolateId::new(),
            Producer::fromNameAndAddress(
                'bittersweet',
                Address::fromStreetNumberZipCodeCityRegionCountry(
                    'via Diqua',
                    '1A',
                    'AB123',
                    'Treviso',
                    'TV',
                    Country::fromStringCode('IT')
                )
            ),
            'dark',
            Percentage::integer(77),
            WrapperType::get(WrapperType::BOX),
            Quantity::grams(100),
            User::new('gigi', 'Zucon')
        );

        $this->repository->approve($chocolate);
    }

    public function testDeleteChocolate(): void
    {
        $chocolate = Chocolate::submit(
            ChocolateId::new(),
            Producer::fromNameAndAddress(
                'bittersweet',
                Address::fromStreetNumberZipCodeCityRegionCountry(
                    'via Diqua',
                    '1A',
                    'AB123',
                    'Treviso',
                    'TV',
                    Country::fromStringCode('IT')
                )
            ),
            'dark',
            Percentage::integer(77),
            WrapperType::get(WrapperType::BOX),
            Quantity::grams(100),
            User::new('gigi', 'Zucon')
        );
        $chocolate->delete(User::newAdministrator('toni', 'Folpet'));

        $statement = Mockery::mock(Statement::class);
        $statement->shouldReceive('execute');

        $this->connection->shouldReceive('executeQuery')->with(
            'INSERT INTO chocolates_history (
                chocolate_id,
                status,
                user_id,
                date_time
            ) VALUES (
                :chocolate_id,
                :status,
                :user_id,
                :date_time
            )',
            [
                'chocolate_id' => (string) $chocolate->id(),
                'status' => $chocolate->status()->getValue(),
                'user_id' => (string) $chocolate->lastTransitionUserId(),
                'date_time' => $chocolate->lastTransitionTime()->format('Y-m-d H:i:s')
            ]
        )->andReturn($statement);

        $this->repository->delete($chocolate);
    }

    public function testDeleteChocolateInWrongState(): void
    {
        $this->expectException(InvalidStatusTransitionException::class);
        $this->expectExceptionMessage('Can not persist the deletion of a chocolate which is not in deleted status');

        $chocolate = Chocolate::submit(
            ChocolateId::new(),
            Producer::fromNameAndAddress(
                'bittersweet',
                Address::fromStreetNumberZipCodeCityRegionCountry(
                    'via Diqua',
                    '1A',
                    'AB123',
                    'Treviso',
                    'TV',
                    Country::fromStringCode('IT')
                )
            ),
            'dark',
            Percentage::integer(77),
            WrapperType::get(WrapperType::BOX),
            Quantity::grams(100),
            User::new('gigi', 'Zucon')
        );

        $this->repository->delete($chocolate);
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
