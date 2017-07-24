<?php

declare(strict_types=1);

namespace App\Domain\Service\Exception;

use App\Domain\Value\UserId;

final class UserNotFoundException extends \RuntimeException implements ExceptionInterface
{
    public static function fromUserId(UserId $id): self
    {
        return new self(sprintf('No user was found for id %s', (string) $id));
    }

    public static function fromUsername(string $name): self
    {
        return new self(sprintf('No user was found dor username %s', $name));
    }
}
