<?php

declare(strict_types=1);

namespace App\Domain\Value;

use App\Domain\Entity\User;

final class StatusTransition implements \JsonSerializable
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

    public static function fromNativeData(
        string $status,
        string $userId,
        string $userUsername,
        string $userPassword,
        bool $userIsAdministrator,
        \DateTimeImmutable $dateTime
    ): self
    {
        return new self(
            Status::get($status),
            User::fromNativeData(
                $userId,
                $userUsername,
                $userPassword,
                $userIsAdministrator
            ),
            $dateTime
        );
    }

    public static function new(Status $status, User $user): self
    {
        return new self($status, $user, date_create_immutable());
    }

    public function status(): Status
    {
        return $this->status;
    }

    public function userId(): UserId
    {
        return $this->user->id();
    }

    public function time(): \DateTimeImmutable
    {
        return $this->dateTime;
    }

    public function jsonSerialize(): array
    {
        return [
            'status' => $this->status->getValue(),
            'userId' => (string) $this->userId(),
            'time' => $this->dateTime->format('Y-m-d H:i:s')
        ];
    }
}
