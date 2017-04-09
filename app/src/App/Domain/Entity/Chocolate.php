<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\App\Domain\Value\Exception\InvalidStatusTransitionException;
use App\App\Domain\Value\Percentage;
use App\App\Domain\Value\Producer;
use App\App\Domain\Value\Quantity;
use App\App\Domain\Value\Status;
use App\App\Domain\Value\WrapperType;
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

    /**
     * @var WrapperType
     */
    private $wrapperType;

    /**
     * @var Quantity
     */
    private $quantity;

    /**
     * @var Status
     */
    private $status;

    private function __construct(
        ChocolateId $id,
        Producer $producer,
        string $description,
        Percentage $cacaoPercentage,
        WrapperType $wrapperType,
        Quantity $quantity,
        Status $status
    ) {
        $this->id = $id;
        $this->producer = $producer;
        $this->description = $description;
        $this->cacaoPercentage = $cacaoPercentage;
        $this->wrapperType = $wrapperType;
        $this->quantity = $quantity;
        $this->status = $status;
    }

    public static function submit(
        ChocolateId $id,
        Producer $producer,
        string $description,
        Percentage $cacaoPercentage,
        WrapperType $wrapperType,
        Quantity $quantity
    ): self
    {
        return new self(
            $id,
            $producer,
            $description,
            $cacaoPercentage,
            $wrapperType,
            $quantity,
            Status::get(Status::APPROVED)
        );
    }

    public function approve(): void
    {
        if ($this->status->getValue() !== Status::SUBMITTED) {
            throw InvalidStatusTransitionException::approveOnlyFromSubmittedStatus();
        }

        $this->status = Status::get(Status::APPROVED);
    }

    public function delete(): void
    {
        $this->status = Status::get(Status::DELETED);
    }
}
