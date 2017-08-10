<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Chocolate;
use App\Domain\Entity\User;
use App\Domain\Service\Exception\ChocolateNotFoundException;
use App\Domain\Value\ChocolateId;
use App\Domain\Value\Percentage;
use App\Domain\Value\Producer;
use App\Domain\Value\Quantity;
use App\Domain\Value\WrapperType;

interface ChocolatesServiceInterface
{
    public function getAll(array $filters = []): array;

    /**
     * @param ChocolateId $id
     * @return Chocolate
     * @throws ChocolateNotFoundException
     */
    public function getChocolate(ChocolateId $id): Chocolate;

    public function submit(
        ChocolateId $id,
        Producer $producer,
        string $description,
        Percentage $cacaoPercentage,
        WrapperType $wrapperType,
        Quantity $quantity,
        User $user
    ): void;

    public function approve(Chocolate $chocolate, User $user): void;

    public function delete(Chocolate $chocolate, User $user): void;
}
