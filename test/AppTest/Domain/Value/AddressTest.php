<?php

declare(strict_types=1);

namespace AppTest\Domain\Value;

use App\Domain\Value\Address;
use App\Domain\Value\Country;
use PHPUnit\Framework\TestCase;

final class AddressTest extends TestCase
{
    public function testFromNativeData(): void
    {
        $street = 'via Daqui';
        $number = '1A';
        $zipCode = 'AB123';
        $city = 'Treviso';
        $region = 'TV';
        $country = 'it';

        $address = Address::fromNativeData(
            $street,
            $number,
            $zipCode,
            $city,
            $region,
            $country
        );

        self::assertInstanceOf(Address::class, $address);
        self::assertSame($street, $address->street());
        self::assertSame($number, $address->streetNumber());
        self::assertSame($zipCode, $address->zipCode());
        self::assertSame($city, $address->city());
        self::assertSame($region, $address->region());
        self::assertSame(strtoupper($country), $address->countryCode()->getValue());
    }

    public function testFromStreetNumberZipCodeCityRegionCountry(): void
    {
        $street = 'via Daqui';
        $number = '1A';
        $zipCode = 'AB123';
        $city = 'Treviso';
        $region = 'TV';
        $country = Country::fromStringCode('it');

        $address = Address::fromStreetNumberZipCodeCityRegionCountry(
            $street,
            $number,
            $zipCode,
            $city,
            $region,
            $country
        );

        self::assertInstanceOf(Address::class, $address);
        self::assertSame($street, $address->street());
        self::assertSame($number, $address->streetNumber());
        self::assertSame($zipCode, $address->zipCode());
        self::assertSame($city, $address->city());
        self::assertSame($region, $address->region());
        self::assertSame($country->code(), $address->countryCode());
    }
}
