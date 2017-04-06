<?php

declare(strict_types=1);

namespace App\App\Domain\Value;

use App\App\Domain\Value\Exception\EmptyTownNameException;

final class Town
{
    /**
     * @var string
     */
    private $name;

    private function __construct(string $name)
    {
        if ('' === $name) {
            throw EmptyTownNameException::new();
        }

        $this->name = $name;
    }

    public static function name(string $name): self
    {
        return new self($name);
    }
}
