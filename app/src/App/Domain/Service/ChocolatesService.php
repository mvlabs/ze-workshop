<?php

declare(strict_types=1);

namespace App\App\Domain\Service;

use App\App\Domain\Service\Exception\ChocolateNotFoundException;
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
}
