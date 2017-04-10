<?php

declare(strict_types=1);

namespace App\App\Domain\Value\Exception;

use App\App\Domain\Entity\User;

final class UnauthorizedUserException extends \DomainException implements ExceptionInterface
{
    public static function shouldBeAdminToApprove(User $user)
    {
        return new self(sprintf(
            'The user %s %s is not authorized to approve a submitted chocolate',
            $user->name(),
            $user->surname()
        ));
    }

    public static function shouldBeAdminToDelete(User $user)
    {
        return new self(sprintf(
            'The user %s %s is not authorized to delete a chocolate',
            $user->name(),
            $user->surname()
        ));
    }
}
