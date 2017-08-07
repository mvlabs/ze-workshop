<?php

declare(strict_types=1);

namespace App\Infrastructure\Hydrators;

final class UsersCollection implements \IteratorAggregate
{
    /**
     * @var array
     */
    private $users;

    public function __construct(array $users)
    {
        $this->users = $users;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->users);
    }
}
