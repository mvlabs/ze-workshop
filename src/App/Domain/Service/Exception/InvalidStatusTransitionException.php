<?php

declare(strict_types=1);

namespace App\Domain\Service\Exception;

/**
 * @codeCoverageIgnore
 */
final class InvalidStatusTransitionException extends \DomainException implements ExceptionInterface
{
    public static function approve(): self
    {
        return new self('Can not persist the approval of a chocolate which is not in approved status');
    }

    public static function delete(): self
    {
        return new self('Can not persist the deletion of a chocolate which is not in deleted status');
    }
}
