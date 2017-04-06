<?php

declare(strict_types=1);

namespace App\App\Domain\Value;

use App\App\Domain\Value\Exception\EmptyProducerNameException;

final class ProducerName
{
    /**
     * @var string
     */
    private $name;

    private function __construct(string $name)
    {
        if ('' === $name) {
            throw EmptyProducerNameException::new();
        }

        $this->name = $name;
    }

    public static function new(string $name): self
    {
        return new self($name);
    }
}
