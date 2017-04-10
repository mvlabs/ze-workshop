<?php

declare(strict_types=1);

namespace App\App\Domain\Value;

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
}
