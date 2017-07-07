<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Value\UserId;

final class User implements \JsonSerializable
{
    /**
     * @var UserId
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var bool
     */
    private $administrator;

    private function __construct(
        UserId $id,
        string $username,
        string $password,
        bool $administrator
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->administrator = $administrator;
    }

    public static function fromNativeData(
        string $id,
        string $username,
        string $password,
        bool $administrator
    ): self
    {
        return new self(
            UserId::fromString($id),
            $username,
            $password,
            $administrator
        );
    }

    public static function new(string $username, string $password): self
    {
        $id = UserId::new();

        return new self($id, $username, $password, false);
    }

    public static function newAdministrator(string $username, string $password): self
    {
        $id = UserId::new();

        return new self($id, $username, $password, true);
    }

    public function isAdministrator(): bool
    {
        return $this->administrator;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function jsonSerialize()
    {
        return [
            'id' => (string) $this->id,
            'username' => $this->username,
            'password' => $this->password,
            'admin' => $this->administrator
        ];
    }
}
