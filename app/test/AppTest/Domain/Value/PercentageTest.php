<?php

declare(strict_types=1);

namespace AppTest\Domain\Value;

use App\Domain\Value\Percentage;
use PHPUnit\Framework\TestCase;

final class PercentageTest extends TestCase
{
    public function testIntegerPercentage()
    {
        $int = 37;

        $percentage = Percentage::integer($int);

        self::assertSame($int, $percentage->toInt());
    }
}
