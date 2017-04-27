<?php

declare(strict_types=1);

namespace AppTest\Domain\Value;

use App\Domain\Value\Country;
use App\Domain\Value\CountryCode;
use PHPUnit\Framework\TestCase;

final class CountryTest extends TestCase
{
    public function testCreateCountryFromString()
    {
        $code = 'IT';
        $country = Country::fromStringCode($code);

        self::assertSame(CountryCode::get($code), $country->code());
        self::assertSame('Italy', $country->name());
    }
}
