<?php

declare(strict_types=1);

namespace AppTest\Domain\Value;

use App\Domain\Value\Quantity;
use PHPUnit\Framework\TestCase;

final class QuantityTest extends TestCase
{
    public function testGrams(): void
    {
        $int = 37;

        $quantity = Quantity::grams($int);

        self::assertSame($int, $quantity->toInt());
    }
}
