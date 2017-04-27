<?php

declare(strict_types=1);

namespace AppTest\Domain\Value;

use App\Domain\Entity\User;
use App\Domain\Value\ChocolateHistory;
use App\Domain\Value\Exception\InvalidChocolateHistoryException;
use App\Domain\Value\Status;
use App\Domain\Value\StatusTransition;
use App\Domain\Value\UserId;
use PHPUnit\Framework\TestCase;

final class ChocolateHistoryTest extends TestCase
{
    public function testChocolateHistoryCannotBeEmpty()
    {
        $this->expectException(InvalidChocolateHistoryException::class);
        $this->expectExceptionMessage('Chocolate history can not be empty');

        ChocolateHistory::transitions([]);
    }

    public function testCreateChocolateHistoryFromTransitions()
    {
        $user = User::new('gigi', 'Zucon');
        $firstTransition = StatusTransition::new(Status::get(Status::SUBMITTED), $user);
        $secondTransition = StatusTransition::new(Status::get(Status::APPROVED), $user);

        $history = ChocolateHistory::transitions([$firstTransition, $secondTransition]);

        self::assertSame(Status::get(Status::APPROVED), $history->currentStatus());
        self::assertSame($user->id(), $history->lastTransitionUserId());
    }

    public function testCreateChocolateHistoryFromTransitionsArray()
    {
        $userId = UserId::new();
        $firstTransition = [
            'status' => Status::SUBMITTED,
            'user_id' => (string) $userId,
            'user_name' => 'gigi',
            'user_surname' => 'Zucon',
            'user_is_administrator' => false,
            'date_time' => date_create_immutable()
        ];
        $time = date_create_immutable();
        $secondTransition = [
            'status' => Status::APPROVED,
            'user_id' => (string) $userId,
            'user_name' => 'gigi',
            'user_surname' => 'Zucon',
            'user_is_administrator' => false,
            'date_time' => $time
        ];

        $history = ChocolateHistory::transitionsArray([$firstTransition, $secondTransition]);

        self::assertSame(Status::get(Status::APPROVED), $history->currentStatus());
        self::assertEquals($userId, $history->lastTransitionUserId());
        self::assertSame($time, $history->lastTransitionTime());
    }

    public function testBeginChocolateHistory()
    {
        $user = User::new('gigi', 'Zucon');
        $transition = StatusTransition::new(Status::get(Status::SUBMITTED), $user);

        $history = ChocolateHistory::beginning($transition);

        self::assertSame(Status::get(Status::SUBMITTED), $history->currentStatus());
        self::assertSame($user->id(), $history->lastTransitionUserId());
    }

    public function testTransitionHistory()
    {
        $firstUser = User::new('gigi', 'Zucon');
        $firstTransition = StatusTransition::new(Status::get(Status::SUBMITTED), $firstUser);
        $secondUser = User::new('toni', 'Folpet');
        $secondTransition = StatusTransition::new(Status::get(Status::APPROVED), $secondUser);

        $history = ChocolateHistory::beginning($firstTransition);
        $history = $history->transition($secondTransition);

        self::assertSame(Status::get(Status::APPROVED), $history->currentStatus());
        self::assertSame($secondUser->id(), $history->lastTransitionUserId());
    }
}
