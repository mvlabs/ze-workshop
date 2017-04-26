<?php

declare(strict_types=1);

namespace AppTest\Domain\Entity;

use App\Domain\Entity\User;
use App\Domain\Value\UserId;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    public function testFromNativeData()
    {
        $id = (string) UserId::new();
        $name = 'Gigi';
        $surname = 'Zucon';
        $isAdmin = false;

        $user = User::fromNativeData(
            $id,
            $name,
            $surname,
            $isAdmin
        );

        self::assertInstanceOf(User::class, $user);
        self::assertSame($id, (string) $user->id());
        self::assertSame($name, $user->name());
        self::assertSame($surname, $user->surname());
        self::assertSame($isAdmin, $user->isAdministrator());
    }
}
