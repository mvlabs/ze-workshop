<?php

declare(strict_types=1);

namespace App\Domain\Value;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class ChocolateId
{
    /**
     * @var UuidInterface
     */
    private $id;

    private function __construct(UuidInterface $id)
    {
        $this->id = $id;
    }

    public static function new(): self
    {
        return new self(Uuid::uuid4());
    }

    public static function fromString(string $chocolateId): self
    {
        return new self(Uuid::fromString($chocolateId));
    }

    public function __toString(): string
    {
        return $this->id->toString();
    }
}
