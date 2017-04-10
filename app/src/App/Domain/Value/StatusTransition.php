<?php

declare(strict_types=1);

namespace App\App\Domain\Value;

use App\App\Domain\Entity\User;

final class StatusTransition
{
    /**
     * @var Status
     */
    private $status;

    /**
     * @var User
     */
    private $user;

    /**
     * @var \DateTimeImmutable
     */
    private $dateTime;

    private function __construct(
        Status $status,
        User $user,
        \DateTimeImmutable $dateTime
    ) {
        $this->status = $status;
        $this->user = $user;
        $this->dateTime = $dateTime;
    }

    public static function new(Status $status, User $user): self
    {
        return new self($status, $user, date_create_immutable());
    }

    public function status(): Status
    {
        return $this->status;
    }
}