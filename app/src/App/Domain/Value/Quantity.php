<?php

declare(strict_types=1);

namespace App\Domain\Value;

use App\Domain\Value\Exception\NegativeQuantityException;

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

    public static function grams(int $quantity): self
    {
        return new self($quantity);
    }

    public function toInt(): int
    {
        return $this->quantity;
    }
}
