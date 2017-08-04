<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\User;
use App\Domain\Service\Exception\UserNotFoundException;
use App\Domain\Value\UserId;

interface UsersServiceInterface
{
    public function register(string $username, string $password): void;

    public function getAll(): array;

    public function getById(UserId $id): ?User;

    /**
     * @throws UserNotFoundException
     */
    public function getByUsername(string $username): User;
}
