<?php

declare(strict_types=1);

namespace App\Domain\Entity\Exception;

/**
 * @codeCoverageIgnore
 */
final class InvalidHistoryException extends \InvalidArgumentException implements ExceptionInterface
{
    public static function fromEmptyHistory(): self
    {
        return new self('A chocolate can not be created with an empty history');
    }
}
