<?php

declare(strict_types=1);

namespace App\Domain\Value\Exception;

/**
 * @codeCoverageIgnore
 */
final class EmptyTownNameException extends \InvalidArgumentException implements ExceptionInterface
{
    public static function new(): self
    {
        return new self('The name of the town must be not empty');
    }
}
