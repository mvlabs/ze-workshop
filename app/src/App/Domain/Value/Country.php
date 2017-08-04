<?php

declare(strict_types=1);

namespace App\Domain\Value;

use App\Domain\Value\Exception\InvalidCountryCodeException;

final class Country
{
    /**
     * @var CountryCode
     */
    private $code;

    private function __construct(CountryCode $code)
    {
        $this->code = $code;
    }

    public static function fromStringCode(string $code): self
    {
        try {
            return new self(CountryCode::byName(strtoupper($code)));
        } catch (\InvalidArgumentException $e) {
            throw InvalidCountryCodeException::fromCountryCode($code, $e);
        }
    }

    public function code(): CountryCode
    {
        return $this->code;
    }

    public function name(): string
    {
        return CountryCodeName::getName($this->code);
    }
}
