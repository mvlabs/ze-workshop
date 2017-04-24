<?php

declare(strict_types=1);

namespace AppTest\Domain\Entity;

use App\Domain\Entity\Chocolate;
use App\Domain\Value\ChocolateId;
use App\Domain\Value\UserId;
use PHPUnit\Framework\TestCase;

final class ChocolateTest extends TestCase
{
    public function testFromNativeData()
    {
        $id = (string) ChocolateId::new();
        $producerName = 'name';
        $producerStreet = 'street';
        $producerStreetNumber = '1A';
        $producerZipCode = 'AB123';
        $producerCity = 'Treviso';
        $producerRegion = 'TV';
        $producerCountry = 'IT';
        $description = 'bittersweet symphony';
        $cacaoPercentage = 73;
        $wrapperType = 'box';
        $quantity = 100;
        $history = [
            [
                'status' => 'submitted',
                'user_id' => (string) UserId::new(),
                'user_name' => 'Gigi',
                'user_surname' => 'Zucon',
                'user_is_administrator' => true,
                'date_time' => date_create_immutable()
            ]
        ];

        $chocolate = Chocolate::fromNativeData(
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

        self::assertInstanceOf(Chocolate::class, $chocolate);
    }
}
