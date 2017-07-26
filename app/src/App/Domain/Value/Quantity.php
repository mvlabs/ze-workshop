<?php

declare(strict_types=1);

namespace App\Domain\Value;

use App\Domain\Value\Exception\InvalidQuantityException;

final class Quantity
{
    /**
     * @var int
     */
    private $quantity;

    private function __construct(int $quantity)
    {
        if ($quantity < 0) {
            throw InvalidQuantityException::fromNegativeInteger($quantity);
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
