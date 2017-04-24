<?php

declare(strict_types=1);

namespace AppTest\Domain\Entity;

use App\Domain\Entity\Chocolate;
use App\Domain\Entity\User;
use App\Domain\Value\Address;
use App\Domain\Value\ChocolateId;
use App\Domain\Value\Country;
use App\Domain\Value\Percentage;
use App\Domain\Value\Producer;
use App\Domain\Value\Quantity;
use App\Domain\Value\UserId;
use App\Domain\Value\WrapperType;
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

        $status = 'submitted';
        $userId = (string) UserId::new();
        $dateTime = date_create_immutable();
        $history = [
            [
                'status' => $status,
                'user_id' => $userId,
                'user_name' => 'Gigi',
                'user_surname' => 'Zucon',
                'user_is_administrator' => true,
                'date_time' => $dateTime
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
        self::assertSame($id, (string) $chocolate->id());
        self::assertSame($producerName, $chocolate->producer()->name());
        self::assertSame($producerStreet, $chocolate->producer()->street());
        self::assertSame($producerStreetNumber, $chocolate->producer()->streetNumber());
        self::assertSame($producerCity, $chocolate->producer()->city());
        self::assertSame($producerRegion, $chocolate->producer()->region());
        self::assertSame($producerCountry, $chocolate->producer()->countryCode()->getValue());
        self::assertSame($description, $chocolate->description());
        self::assertSame($cacaoPercentage, $chocolate->cacaoPercentage()->toInt());
        self::assertSame($wrapperType, $chocolate->wrapperType()->getValue());
        self::assertSame($quantity, $chocolate->quantity()->toInt());
        self::assertSame($status, $chocolate->status()->getValue());
        self::assertSame($userId, (string) $chocolate->lastTransitionUserId());
        self::assertSame($dateTime, $chocolate->lastTransitionTime());
    }

    public function testSubmit()
    {
        $id = ChocolateId::new();
        $country = Country::fromStringCode('it');
        $address = Address::fromStreetNumberZipCodeCityRegionCountry(
            'street',
            '1A',
            'AB123',
            'Treviso',
            'TV',
            $country
        );
        $producer = Producer::fromNameAndAddress('name',$address);
        $description = 'bittersweet symphony';
        $cacaoPercentage = Percentage::integer(73);
        $wrapperType = WrapperType::get('box');
        $quantity = Quantity::grams(100);
        $user = User::new('gigi', 'Zucon');

        $chocolate = Chocolate::submit(
            $id,
            $producer,
            $description,
            $cacaoPercentage,
            $wrapperType,
            $quantity,
            $user
        );

        self::assertSame($id, $chocolate->id());
        self::assertSame($producer, $chocolate->producer());
        self::assertSame($description, $chocolate->description());
        self::assertSame($cacaoPercentage, $chocolate->cacaoPercentage());
        self::assertSame($wrapperType, $chocolate->wrapperType());
        self::assertSame($quantity, $chocolate->quantity());
        self::assertSame($user->id(), $chocolate->lastTransitionUserId());
    }
}
