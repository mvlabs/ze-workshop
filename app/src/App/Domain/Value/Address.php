<?php

declare(strict_types=1);

namespace App\App\Domain\Value;

final class Address
{
    /**
     * @var string
     */
    private $street;

    /**
     * @var string
     */
    private $number;

    /**
     * @var string
     */
    private $zipCode;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $region;

    /**
     * @var Country
     */
    private $country;

    private function __construct(
        string $street,
        string $number,
        string $zipCode,
        string $city,
        string $region,
        Country $country
    ) {
        $this->street = $street;
        $this->number = $number;
        $this->zipCode = $zipCode;
        $this->city = $city;
        $this->region = $region;
        $this->country = $country;
    }
}
