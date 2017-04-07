<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\App\Domain\Value\Percentage;
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

    /**
     * @var string
     */
    private $description;

    /**
     * @var Percentage
     */
    private $cacaoPercentage;

    private function __construct(
        ChocolateId $id,
        Producer $producer,
        string $description,
        Percentage $cacaoPercentage
    ) {
        $this->id = $id;
        $this->producer = $producer;
        $this->description = $description;
        $this->cacaoPercentage = $cacaoPercentage;
    }
}
