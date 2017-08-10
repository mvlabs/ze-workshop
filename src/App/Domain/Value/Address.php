<?php

declare(strict_types=1);

namespace App\Domain\Value;

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

    public static function fromStreetNumberZipCodeCityRegionCountry(
        string $street,
        string $number,
        string $zipCode,
        string $city,
        string $region,
        Country $country
    ): self
    {
        return new self($street, $number, $zipCode, $city, $region, $country);
    }

    public static function fromNativeData(
        string $street,
        string $number,
        string $zipCode,
        string $city,
        string $region,
        string $country
    ): self
    {
        return new self(
            $street,
            $number,
            $zipCode,
            $city,
            $region,
            Country::fromStringCode($country)
        );
    }

    public function street(): string
    {
        return $this->street;
    }

    public function streetNumber(): string
    {
        return $this->number;
    }

    public function zipCode(): string
    {
        return $this->zipCode;
    }

    public function city(): string
    {
        return $this->city;
    }

    public function region(): string
    {
        return $this->region;
    }

    public function countryCode(): CountryCode
    {
        return $this->country->code();
    }
}
