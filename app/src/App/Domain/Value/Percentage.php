<?php

declare(strict_types=1);

namespace App\App\Domain\Value;

use App\App\Domain\Value\Exception\InvalidPercentageException;

final class Percentage
{
    /**
     * @var int
     */
    private $percentage;

    private function __construct(int $percentage)
    {
        if ($percentage < 0) {
            throw InvalidPercentageException::lessThanZero($percentage);
        }

        if ($percentage > 100) {
            throw InvalidPercentageException::overOneHundred($percentage);
        }

        $this->percentage = $percentage;
    }

    public static function integer(int $percentage): self
    {
        return new self($percentage);
    }

    public function toInt(): int
    {
        return $this->percentage;
    }
}
