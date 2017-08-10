<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Chocolate;
use App\Domain\Value\ChocolateId;

interface Chocolates
{
    public function all(array $filters = []): array;

    public function findById(ChocolateId $id): ?Chocolate;

    public function add(Chocolate $chocolate): void;

    public function approve(Chocolate $chocolate): void;

    public function delete(Chocolate $chocolate): void;
}
