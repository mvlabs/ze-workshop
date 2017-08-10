<?php

declare(strict_types=1);

namespace App\Domain\Value\Exception;

/**
 * @codeCoverageIgnore
 */
final class EmptyProducerNameException extends \InvalidArgumentException implements ExceptionInterface
{
    public static function new(): self
    {
        return new self('The producer name must not be empty');
    }
}
