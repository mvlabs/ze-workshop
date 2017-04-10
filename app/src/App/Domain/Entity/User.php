<?php

declare(strict_types=1);

namespace App\App\Domain\Entity;

use App\App\Domain\Value\UserId;

final class User
{
    /**
     * @var UserId
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $surname;

    /**
     * @var bool
     */
    private $administrator;

    private function __construct(
        UserId $id,
        string $name,
        string $surname
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->administrator = false;
    }

    public static function new(string $name, string $surname): self
    {
        $id = UserId::new();

        return new self($id, $name, $surname, false);
    }

    public static function newAdministrator(string $name, string $surname): self
    {
        $id = UserId::new();

        return new self($id, $name, $surname, true);
    }

    public function isAdministrator(): bool
    {
        return $this->administrator;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function surname(): string
    {
        return $this->surname;
    }
}
