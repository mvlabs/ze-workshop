<?php

declare(strict_types=1);

namespace App\Domain\Value\Exception;

/**
 * @codeCoverageIgnore
 */
final class InvalidStatusTransitionException extends \DomainException implements ExceptionInterface
{
    public static function approveOnlyFromSubmittedStatus(): self
    {
        return new self('You can approve only a chocolate in submitted status');
    }
}
