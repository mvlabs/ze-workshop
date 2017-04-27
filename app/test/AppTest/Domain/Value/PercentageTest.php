<?php

declare(strict_types=1);

namespace AppTest\Domain\Value;

use App\Domain\Value\Exception\InvalidPercentageException;
use App\Domain\Value\Percentage;
use PHPUnit\Framework\TestCase;

final class PercentageTest extends TestCase
{
    public function testIntegerPercentage(): void
    {
        $int = 37;

        $percentage = Percentage::integer($int);

        self::assertSame($int, $percentage->toInt());
    }

    public function testNegativePercentageFails(): void
    {
        $percentage = -1;

        $this->expectException(InvalidPercentageException::class);
        $this->expectExceptionMessage(sprintf(
            'A percentage can not be smaller than zero. Received %s',
            $percentage
        ));

        Percentage::integer($percentage);
    }

    public function testPercentageOver100Fails(): void
    {
        $percentage = 101;

        $this->expectException(InvalidPercentageException::class);
        $this->expectExceptionMessage(sprintf(
            'A percentage can not be bigger than one hundred. Received %s',
            $percentage
        ));

        Percentage::integer($percentage);
    }
}
