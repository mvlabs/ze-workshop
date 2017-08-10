<?php

declare(strict_types=1);

namespace AppTest\Domain\Value;

use App\Domain\Value\CountryCode;
use App\Domain\Value\CountryCodeName;
use PHPUnit\Framework\TestCase;

final class CountryCodeNameTest extends TestCase
{
    public function testGetName(): void
    {
        $code = CountryCode::get('IT');

        self::assertSame('Italy', CountryCodeName::getName($code));
    }
}
