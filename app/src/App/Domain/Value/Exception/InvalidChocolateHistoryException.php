<?php

declare(strict_types=1);

namespace App\Domain\Value\Exception;

/**
 * @codeCoverageIgnore
 */
final class InvalidChocolateHistoryException extends \RuntimeException implements ExceptionInterface
{
    /**
     * @param mixed $statusTransition
     * @return self
     */
    public static function invalidStatusTransition($statusTransition): self
    {
        return new self(sprintf(
            'Chocolate history is composed of StatusTransitions, %s received instead',
            get_class($statusTransition)
        ));
    }

    public static function invalidEmptyHistoryException(): self
    {
        return new self('Chocolate history can not be empty');
    }
}
