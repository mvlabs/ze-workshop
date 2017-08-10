<?php

declare(strict_types=1);

namespace AppTest\Domain\Value;

use App\Domain\Value\Address;
use App\Domain\Value\Country;
use App\Domain\Value\CountryCode;
use App\Domain\Value\Producer;
use PHPUnit\Framework\TestCase;

final class ProducerTest extends TestCase
{
    public function testCreateFromNameAndAddress(): void
    {
        $name = 'bittersweet';
        $address = Address::fromStreetNumberZipCodeCityRegionCountry(
            'via Diqua',
            '1A',
            'AB123',
            'Treviso',
            'TV',
            Country::fromStringCode('IT')
        );

        $producer = Producer::fromNameAndAddress($name, $address);

        self::assertSame($name, $producer->name());
        self::assertSame($address->street(), $producer->street());
        self::assertSame($address->streetNumber(), $producer->streetNumber());
        self::assertSame($address->zipCode(), $producer->zipCode());
        self::assertSame($address->city(), $producer->city());
        self::assertSame($address->region(), $producer->region());
        self::assertSame($address->countryCode(), $producer->countryCode());
    }

    public function testCreateFromNativeData(): void
    {
        $name = 'bittersweet';
        $street = 'via Diqua';
        $number = '1A';
        $zipCode = 'AB123';
        $city = 'Treviso';
        $region = 'TV';
        $country = 'IT';

        $producer = Producer::fromNativeData(
            $name,
            $street,
            $number,
            $zipCode,
            $city,
            $region,
            $country
        );

        self::assertSame($name, $producer->name());
        self::assertSame($street, $producer->street());
        self::assertSame($number, $producer->streetNumber());
        self::assertSame($zipCode, $producer->zipCode());
        self::assertSame($city, $producer->city());
        self::assertSame($region, $producer->region());
        self::assertEquals(CountryCode::get($country), $producer->countryCode());
    }
}
