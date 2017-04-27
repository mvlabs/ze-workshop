<?php

declare(strict_types=1);

namespace App\Domain\Value\Exception;

/**
 * @codeCoverageIgnore
 */
final class InvalidPercentageException extends \InvalidArgumentException implements ExceptionInterface
{
    public static function lessThanZero(int $percentage): self
    {
        return new self(sprintf(
            'A percentage can not be smaller than zero. Received %s',
            $percentage
        ));
    }

    public static function overOneHundred(int $percentage): self
    {
        return new self(sprintf(
            'A percentage can not be bigger than one hundred. Received %s',
            $percentage
        ));
    }
}
