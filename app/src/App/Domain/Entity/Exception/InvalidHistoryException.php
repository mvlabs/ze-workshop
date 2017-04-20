<?php

declare(strict_types=1);

namespace App\App\Domain\Entity\Exception;

final class InvalidHistoryException extends \InvalidArgumentException implements ExceptionInterface
{
    public static function fromEmptyHistory()
    {
        return new self('A chocolate can not be created with an empty history');
    }
}
