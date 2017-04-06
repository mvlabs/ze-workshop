<?php

declare(strict_types=1);

namespace App\App\Domain\Value;

final class Producer
{
    /**
     * @var ProducerName
     */
    private $name;

    /**
     * @var Address
     */
    private $address;

    private function __construct(
        ProducerName $name,
        Address $address
    ) {
        $this->name = $name;
        $this->address = $address;
    }
}
