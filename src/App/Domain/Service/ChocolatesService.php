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
use App\Infrastructure\Repository\Chocolates;

final class ChocolatesService implements ChocolatesServiceInterface
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
    public function getAll(array $filters = []): array
    {
        return $this->chocolates->all($filters);
    }

    /**
     * @inheritdoc
     */
    public function getChocolate(ChocolateId $id): Chocolate
    {
        $chocolate = $this->chocolates->findById($id);

        if (!$chocolate instanceof Chocolate) {
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

    public function approve(Chocolate $chocolate, User $user): void
    {
        $chocolate->approve($user);

        $this->chocolates->approve($chocolate);
    }

    public function delete(Chocolate $chocolate, User $user): void
    {
        $chocolate->delete($user);

        $this->chocolates->delete($chocolate);
    }
}
