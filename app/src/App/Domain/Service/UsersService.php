<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\User;
use App\Infrastructure\Repository\Users;

final class UsersService
{
    /**
     * @var Users
     */
    private $users;

    public function __construct(Users $users)
    {
        $this->users = $users;
    }

    public function register(
        string $name,
        string $surname,
        bool $isAdministrator = false
    ): void
    {
        if ($isAdministrator) {
            $user = User::newAdministrator($name, $surname);
        } else {
            $user = User::new($name, $surname);
        }

        $this->users->add($user);
    }
}