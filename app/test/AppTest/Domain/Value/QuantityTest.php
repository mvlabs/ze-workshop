<?php

declare(strict_types=1);

namespace AppTest\Domain\Value;

use App\Domain\Value\Exception\NegativeQuantityException;
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

    public function testNegativeQuantityFails(): void
    {
        $this->expectException(NegativeQuantityException::class);
        $this->expectExceptionMessage(sprintf('A quantity must be non negative. Received %s', -1));

        Quantity::grams(-1);
    }
}
