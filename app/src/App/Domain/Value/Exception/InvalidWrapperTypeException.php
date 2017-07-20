<?php

declare(strict_types=1);

namespace App\Domain\Value\Exception;

final class InvalidWrapperTypeException extends \InvalidArgumentException implements ExceptionInterface
{
    public static function fromWrapperType(string $wrapperType, \Throwable $previous)
    {
        return new self(sprintf('There is no wrapper type called %s', $wrapperType), 0, $previous);
    }
}
