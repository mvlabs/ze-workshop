<?php

declare(strict_types=1);

namespace App\Domain\Value;

use App\Domain\Value\Exception\InvalidChocolateHistoryException;

final class ChocolateHistory implements \JsonSerializable
{
    /**
     * @var \SplQueue
     */
    private $statusTransitions;

    private function __construct(array $transitions)
    {
        if (empty($transitions)) {
            throw InvalidChocolateHistoryException::invalidEmptyHistoryException();
        }

        $this->statusTransitions = new \SplQueue();

        foreach ($transitions as $transition) {
            if (!$transition instanceof StatusTransition) {
                throw InvalidChocolateHistoryException::invalidStatusTransition($transition);
            }

            $this->statusTransitions->enqueue($transition);
        }
    }

    public static function transitions(array $transitions): self
    {
        return new self($transitions);
    }

    public static function transitionsArray(array $transitions): self
    {
        return new self(array_map(function (array $transition) {
            return StatusTransition::fromNativeData(
                $transition['status'],
                $transition['user_id'],
                $transition['user_username'],
                $transition['user_password'],
                $transition['user_is_administrator'],
                $transition['date_time']
            );
        }, $transitions));
    }

    public static function beginning(StatusTransition $firstStatus): self
    {
        return new self([$firstStatus]);
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

    public function jsonSerialize(): array
    {
        $jsonHistory = [];

        foreach ($this->statusTransitions as $transition) {
            /** @var StatusTransition $transition */
            $jsonHistory[] = $transition->jsonSerialize();
        }

        return $jsonHistory;
    }
}
