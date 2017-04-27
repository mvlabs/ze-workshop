<?php

declare(strict_types=1);

namespace App\Domain\Value\Exception;

use App\Domain\Entity\User;

/**
 * @codeCoverageIgnore
 */
final class UnauthorizedUserException extends \DomainException implements ExceptionInterface
{
    public static function shouldBeAdminToApprove(User $user): self
    {
        return new self(sprintf(
            'The user %s %s is not authorized to approve a submitted chocolate',
            $user->name(),
            $user->surname()
        ));
    }

    public static function shouldBeAdminToDelete(User $user): self
    {
        return new self(sprintf(
            'The user %s %s is not authorized to delete a chocolate',
            $user->name(),
            $user->surname()
        ));
    }
}
