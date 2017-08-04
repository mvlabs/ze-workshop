<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\User;
use App\Domain\Service\Exception\UserNotFoundException;
use App\Domain\Value\UserId;
use App\Infrastructure\Repository\Users;

final class UsersService implements UsersServiceInterface
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

    public function getById(UserId $id): ?User
    {
        return $this->users->findById($id);
    }

    /**
     * @inheritdoc
     */
    public function getByUsername(string $username): User
    {
        $user = $this->users->findByUsername($username);

        if (!$user instanceof User) {
            throw UserNotFoundException::fromUsername($username);
        }

        return $user;
    }
}
