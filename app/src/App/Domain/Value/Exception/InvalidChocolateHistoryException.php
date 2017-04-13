<?php

declare(strict_types=1);

namespace App\App\Domain\Value\Exception;

final class InvalidChocolateHistoryException extends \RuntimeException implements ExceptionInterface
{
    public static function invalidStatusTransition($statusTransition): self
    {
        return new self(sprintf(
            'Chocolate history is composed of StatusTransitions, %s received instead',
            get_class($statusTransition)
        ));
    }
}
