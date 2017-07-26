<?php

declare(strict_types=1);

namespace App\Domain\Value\Exception;

/**
 * @codeCoverageIgnore
 */
final class InvalidQuantityException extends \InvalidArgumentException implements ExceptionInterface
{
    public static function fromNegativeInteger(int $quantity): self
    {
        return new self(sprintf('A quantity must be non negative. Received %s', $quantity));
    }

    public static function nonIntegerValue($quantity): self
    {
        return new self(sprintf('A quantity could not be created from non integer value %s', $quantity));
    }
}
