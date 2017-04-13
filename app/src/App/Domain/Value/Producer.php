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

    public static function fromNativeData(
        string $name,
        string $street,
        string $streetNumber,
        string $zipCode,
        string $city,
        string $region,
        string $country
    ): self
    {
        return new self(
            $name,
            Address::fromNativeData(
                $street,
                $streetNumber,
                $zipCode,
                $city,
                $region,
                $country
            )
        );
    }
}
