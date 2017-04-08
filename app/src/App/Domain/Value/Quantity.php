<?php

declare(strict_types=1);

namespace App\App\Domain\Value;

use App\App\Domain\Value\Exception\NegativeQuantityException;

final class Quantity
{
    /**
     * @var int
     */
    private $quantity;

    private function __construct(int $quantity)
    {
        if ($quantity < 0) {
            throw NegativeQuantityException::fromInteger($quantity);
        }

        $this->quantity = $quantity;
    }

    public static function integer(int $quantity)
    {
        return new self($quantity);
    }
}
