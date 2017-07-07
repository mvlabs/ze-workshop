<?php

declare(strict_types=1);

namespace AppTest\Domain\Value;

use App\Domain\Entity\User;
use App\Domain\Value\Status;
use App\Domain\Value\StatusTransition;
use App\Domain\Value\UserId;
use PHPUnit\Framework\TestCase;

final class StatusTransitionTest extends TestCase
{
    public function testNew(): void
    {
        $status = Status::get(Status::SUBMITTED);
        $user = User::new('gigi', 'Zucon');

        $transition = StatusTransition::new($status, $user);

        self::assertSame($status, $transition->status());
        self::assertSame($user->id(), $transition->userId());
    }

    public function testFromNativeData(): void
    {
        $status = Status::SUBMITTED;
        $userId = UserId::new();
        $userUsername = 'gigi';
        $userPassword = 'Zucon';
        $userIsAdmin = false;
        $dateTime = date_create_immutable();

        $transition = StatusTransition::fromNativeData(
            $status,
            (string) $userId,
            $userUsername,
            $userPassword,
            $userIsAdmin,
            $dateTime
        );

        self::assertSame(Status::get($status), $transition->status());
        self::assertEquals($userId, $transition->userId());
        self::assertSame($dateTime, $transition->time());
    }
}
