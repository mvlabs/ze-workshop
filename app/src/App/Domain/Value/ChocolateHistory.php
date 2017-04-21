<?php

declare(strict_types=1);

namespace App\Domain\Value;

use App\Domain\Value\Exception\InvalidChocolateHistoryException;

final class ChocolateHistory
{
    /**
     * @var \SplQueue
     */
    private $statusTransitions;

    private function __construct()
    {
        $this->statusTransitions = new \SplQueue();
    }

    public static function transitions(array $transitions): self
    {
        $history = new self();

        foreach ($transitions as $transition) {
            if (!$transition instanceof StatusTransition) {
                throw InvalidChocolateHistoryException::invalidStatusTransition($transition);
            }

            $history->statusTransitions->enqueue($transition);
        }

        return $history;
    }

    public static function transitionsArray(array $transitions): self
    {
        return self::transitions(array_map(function (array $transition) {
            return StatusTransition::fromNativeData(
                $transition['status'],
                $transition['user_id'],
                $transition['user_name'],
                $transition['user_surname'],
                $transition['user_is_administrator'],
                $transition['date_time']
            );
        }, $transitions));
    }

    public static function beginning(StatusTransition $firstStatus): self
    {
        $instance = new self();

        $instance->statusTransitions->enqueue($firstStatus);

        return $instance;
    }

    public function currentStatus(): Status
    {
        /** @var StatusTransition $statusTransition */
        $statusTransition = $this->statusTransitions->top();

        return $statusTransition->status();
    }

    public function transition(StatusTransition $transition): self
    {
        $instance = clone $this;

        $instance->statusTransitions->enqueue($transition);

        return $instance;
    }

    public function isEmpty(): bool
    {
        return $this->statusTransitions->isEmpty();
    }

    public function lastTransitionUserId(): UserId
    {
        /** @var StatusTransition $statusTransition */
        $statusTransition = $this->statusTransitions->top();

        return $statusTransition->userId();
    }

    public function lastTransitionTime(): \DateTimeImmutable
    {
        /** @var StatusTransition $statusTransition */
        $statusTransition = $this->statusTransitions->top();

        return $statusTransition->time();
    }
}
