<?php

declare(strict_types=1);

namespace App\Domain\Value;

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
        return new self(CountryCode::byName(strtoupper($code)));
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
