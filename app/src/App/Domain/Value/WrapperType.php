<?php

declare(strict_types=1);

namespace App\Domain\Value;

use App\Domain\Value\Exception\InvalidWrapperTypeException;
use MabeEnum\Enum;

final class WrapperType extends Enum
{
    const PAPER = 'paper';
    const BOX = 'box';
    const BAND = 'band';

    public static function fromString(string $type): self
    {
        try {
            return self::get($type);
        } catch (\InvalidArgumentException $e) {
            throw new InvalidWrapperTypeException($type, $e);
        }
    }
}
