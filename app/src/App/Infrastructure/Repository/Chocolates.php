<?php

declare(strict_types=1);

namespace App\App\Infrastructure\Repository;

use App\App\Domain\Value\Status;
use App\App\Domain\Value\UserId;
use App\Domain\Entity\Chocolate;
use App\Domain\Value\ChocolateId;
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

    /**
     * @return Chocolate[]
     */
    public function all(): array
    {
        $allChocolatesStatement = $this->connection->executeQuery(
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
        );

        return $allChocolatesStatement->fetchAll(
            \PDO::FETCH_FUNC,
            [self::class, 'createChocolate']
        );
    }

    public function findById(ChocolateId $id): Chocolate
    {
        $chocolateStatement = $this->connection->executeQuery(
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
                'id' => (string) $id
            ]
        );

        $chocolateArray = $chocolateStatement->fetch(\PDO::FETCH_ASSOC);

        return $this->createChocolate(
            $chocolateArray['id'],
            $chocolateArray['name'],
            $chocolateArray['street'],
            $chocolateArray['street_number'],
            $chocolateArray['zip_code'],
            $chocolateArray['city'],
            $chocolateArray['region'],
            $chocolateArray['country'],
            $chocolateArray['description'],
            $chocolateArray['cacao_percentage'],
            $chocolateArray['wrapper_type'],
            $chocolateArray['quantity'],
            $chocolateArray['history']
        );
    }

    private function createChocolate(
        string $id,
        string $producerName,
        string $producerStreet,
        string $producerStreetNumber,
        string $producerZipCode,
        string $producerCity,
        string $producerRegion,
        string $producerCountry,
        string $description,
        int $cacaoPercentage,
        string $wrapperType,
        int $quantity,
        array $history
    ): Chocolate
    {
        $history = array_chunk($history, 6);
        $history = array_map(function (array $transition) {
            return [
                'status' => $transition[0],
                'user_id' => $transition[1],
                'user_name' => $transition[2],
                'user_surname' => $transition[3],
                'user_is_administrator' => json_decode($transition[4]),
                'date_time' => date_create_immutable($transition[5])
            ];
        }, $history);

        return Chocolate::fromNativeData(
            $id,
            $producerName,
            $producerStreet,
            $producerStreetNumber,
            $producerZipCode,
            $producerCity,
            $producerRegion,
            $producerCountry,
            $description,
            $cacaoPercentage,
            $wrapperType,
            $quantity,
            $history
        );
    }

    public function transition(
        ChocolateId $id,
        UserId $userId,
        Status $status
    ): void
    {
        $statement = $this->connection->executeQuery(
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
                'chocolate_id' => (string) $id,
                'status' => $status->getValue(),
                'user_id' => (string) $userId,
                'date_time' => date_create_immutable()
            ]
        );

        $statement->execute();
    }
}
