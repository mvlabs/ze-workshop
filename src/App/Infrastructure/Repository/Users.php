<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\User;
use App\Domain\Value\UserId;

interface Users
{
    public function add(User $user): void;

    public function all(): array;

    public function findById(UserId $id): ?User;

    public function findByUsername(string $username): ?User;
}
