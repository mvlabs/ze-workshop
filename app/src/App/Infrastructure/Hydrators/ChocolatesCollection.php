<?php

declare(strict_types=1);

namespace App\Infrastructure\Hydrators;

final class ChocolatesCollection implements \IteratorAggregate
{
    /**
     * @var array
     */
    private $chocolates;

    public function __construct(array $chocolates)
    {
        $this->chocolates = $chocolates;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->chocolates);
    }
}
