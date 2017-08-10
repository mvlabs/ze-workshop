<?php

declare(strict_types=1);

namespace AppTest\Domain\Entity;

use App\Domain\Entity\User;
use App\Domain\Value\UserId;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    public function testFromNativeData(): void
    {
        $id = (string) UserId::new();
        $username = 'Gigi';
        $password = 'Zucon';
        $isAdmin = false;

        $user = User::fromNativeData(
            $id,
            $username,
            $password,
            $isAdmin
        );

        self::assertInstanceOf(User::class, $user);
        self::assertSame($id, (string) $user->id());
        self::assertSame($username, $user->username());
        self::assertSame($password, $user->password());
        self::assertSame($isAdmin, $user->isAdministrator());
    }

    public function testNew(): void
    {
        $username = 'Gigi';
        $password = 'Zucon';

        $user = User::new($username, $password);

        self::assertInstanceOf(UserId::class, $user->id());
        self::assertSame($username, $user->username());
        self::assertSame($password, $user->password());
        self::assertFalse($user->isAdministrator());
    }

    public function testNewAdministrator(): void
    {
        $username = 'Gigi';
        $password = 'Zucon';

        $user = User::newAdministrator($username, $password);

        self::assertInstanceOf(UserId::class, $user->id());
        self::assertSame($username, $user->username());
        self::assertSame($password, $user->password());
        self::assertTrue($user->isAdministrator());
    }
}
