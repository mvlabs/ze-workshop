<?php

declare(strict_types=1);

namespace App\Domain\Value;

final class Producer implements \JsonSerializable
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

    public static function fromNameAndAddress(
        string $name,
        Address $address
    ): self
    {
        return new self($name, $address);
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

    public function name(): string
    {
        return $this->name;
    }

    public function street(): string
    {
        return $this->address->street();
    }

    public function streetNumber(): string
    {
        return $this->address->streetNumber();
    }

    public function zipCode(): string
    {
        return $this->address->zipCode();
    }

    public function city(): string
    {
        return $this->address->city();
    }

    public function region(): string
    {
        return $this->address->region();
    }

    public function countryCode(): CountryCode
    {
        return $this->address->countryCode();
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'street' => $this->street(),
            'streetNumber' => $this->streetNumber(),
            'zipCode' => $this->zipCode(),
            'city' => $this->city(),
            'region' => $this->region(),
            'country' => $this->countryCode()->getValue()
        ];
    }
}
