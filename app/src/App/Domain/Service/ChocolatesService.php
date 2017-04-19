<?php

declare(strict_types=1);

namespace App\App\Domain\Service;

use App\App\Domain\Entity\User;
use App\App\Domain\Service\Exception\ChocolateNotFoundException;
use App\App\Domain\Value\Percentage;
use App\App\Domain\Value\Producer;
use App\App\Domain\Value\Quantity;
use App\App\Domain\Value\WrapperType;
use App\App\Infrastructure\Repository\Chocolates;
use App\Domain\Entity\Chocolate;
use App\Domain\Value\ChocolateId;

final class ChocolatesService
{
    /**
     * @var Chocolates
     */
    private $chocolates;

    public function __construct(Chocolates $chocolates)
    {
        $this->chocolates = $chocolates;
    }

    /**
     * @return Chocolate[]
     */
    public function getAll(): array
    {
        return $this->chocolates->all();
    }

    /**
     * @param ChocolateId $id
     * @return Chocolate
     * @throws ChocolateNotFoundException
     */
    public function getChocolate(ChocolateId $id): Chocolate
    {
        $chocolate = $this->chocolates->findById($id);

        if (!$chocolate instanceof  Chocolate) {
            throw ChocolateNotFoundException::fromChocolateId($id);
        }

        return $chocolate;
    }

    public function submit(
        ChocolateId $id,
        Producer $producer,
        string $description,
        Percentage $cacaoPercentage,
        WrapperType $wrapperType,
        Quantity $quantity,
        User $user
    ): void
    {
        $chocolate = Chocolate::submit(
            $id,
            $producer,
            $description,
            $cacaoPercentage,
            $wrapperType,
            $quantity,
            $user
        );

        $this->chocolates->add($chocolate);
    }
}
