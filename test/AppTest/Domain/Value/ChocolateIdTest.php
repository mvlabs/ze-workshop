<?php

declare(strict_types=1);

namespace AppTest\Domain\Value;

use App\Domain\Value\ChocolateId;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class ChocolateIdTest extends TestCase
{
    public function testFromObjectToStringAndBack(): void
    {
        $vehicleId = ChocolateId::new();

        self::assertEquals($vehicleId, ChocolateId::fromString((string) $vehicleId));
    }

    public function testFromStringAndBack(): void
    {
        $uuid = (string) Uuid::uuid4();

        self::assertEquals($uuid, (string) ChocolateId::fromString($uuid));
    }
}
