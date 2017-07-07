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
        string $username,
        string $password
    ): void
    {
        $this->users->add(User::new($username, $password));
    }

    public function getAll(): array
    {
        return $this->users->all();
    }
}
