<?php

declare(strict_types=1);

namespace App\App\Domain\Value\Exception;

final class NegativeQuantityException extends \InvalidArgumentException implements ExceptionInterface
{
    public static function fromInteger(int $quantity)
    {
        return new self(sprintf('A quantity must be non negative. Received %s', $quantity));
    }
}
