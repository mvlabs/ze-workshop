<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\User;

interface Users
{
    public function add(User $user): void;

    public function all();
}
