<?php

declare(strict_types=1);

namespace App\App\Domain\Value;

final class Producer
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Address
     */
    private $address;

    private function __construct(
        string $name,
        Address $address
    ) {
        $this->name = $name;
        $this->address = $address;
    }
}
