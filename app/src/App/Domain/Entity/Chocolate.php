<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\App\Domain\Value\Producer;
use App\Domain\Value\ChocolateId;

final class Chocolate
{
    /**
     * @var ChocolateId
     */
    private $id;

    /**
     * @var Producer
     */
    private $producer;

    private function __construct(
        ChocolateId $id,
        Producer $producer
    ) {
        $this->id = $id;
        $this->producer = $producer;
    }
}
