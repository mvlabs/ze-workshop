<?php

declare(strict_types=1);

namespace AppTest\Domain\Value;

use App\Domain\Value\UserId;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class UserIdTest extends TestCase
{
    public function testFromObjectToStringAndBack(): void
    {
        $vehicleId = UserId::new();

        self::assertEquals($vehicleId, UserId::fromString((string) $vehicleId));
    }

    public function testFromStringAndBack(): void
    {
        $uuid = (string) Uuid::uuid4();

        self::assertEquals($uuid, (string) UserId::fromString($uuid));
    }
}
