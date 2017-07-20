<?php

declare(strict_types=1);

namespace App\Domain\Value\Exception;

final class InvalidCountryCodeException extends \InvalidArgumentException implements ExceptionInterface
{
    public static function fromCountryCode(string $code, \Throwable $previous)
    {
        return new self(sprintf('No country exists with code %s', $code), 0, $previous);
    }
}
