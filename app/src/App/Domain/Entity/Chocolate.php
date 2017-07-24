<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Value\ChocolateHistory;
use App\Domain\Value\ChocolateId;
use App\Domain\Value\Exception\InvalidStatusTransitionException;
use App\Domain\Value\Exception\UnauthorizedUserException;
use App\Domain\Value\Percentage;
use App\Domain\Value\Producer;
use App\Domain\Value\Quantity;
use App\Domain\Value\Status;
use App\Domain\Value\StatusTransition;
use App\Domain\Value\UserId;
use App\Domain\Value\WrapperType;

final class Chocolate implements \JsonSerializable
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
     * @var ChocolateHistory
     */
    private $history;

    private function __construct(
        ChocolateId $id,
        Producer $producer,
        string $description,
        Percentage $cacaoPercentage,
        WrapperType $wrapperType,
        Quantity $quantity,
        ChocolateHistory $history
    ) {
        $this->id = $id;
        $this->producer = $producer;
        $this->description = $description;
        $this->cacaoPercentage = $cacaoPercentage;
        $this->wrapperType = $wrapperType;
        $this->quantity = $quantity;
        $this->history = $history;
    }

    public static function fromNativeData(
        string $id,
        string $producerName,
        string $producerStreet,
        string $producerStreetNumber,
        string $producerZipCode,
        string $producerCity,
        string $producerRegion,
        string $producerCountry,
        string $description,
        int $cacaoPercentage,
        string $wrapperType,
        int $quantity,
        array $history
    ): self
    {
        return new self(
            ChocolateId::fromString($id),
            Producer::fromNativeData(
                $producerName,
                $producerStreet,
                $producerStreetNumber,
                $producerZipCode,
                $producerCity,
                $producerRegion,
                $producerCountry
            ),
            $description,
            Percentage::integer($cacaoPercentage),
            WrapperType::get($wrapperType),
            Quantity::grams($quantity),
            ChocolateHistory::transitionsArray($history)
        );
    }

    public static function submit(
        ChocolateId $id,
        Producer $producer,
        string $description,
        Percentage $cacaoPercentage,
        WrapperType $wrapperType,
        Quantity $quantity,
        User $user
    ): self
    {
        return new self(
            $id,
            $producer,
            $description,
            $cacaoPercentage,
            $wrapperType,
            $quantity,
            ChocolateHistory::beginning(
                StatusTransition::new(
                    Status::get(Status::SUBMITTED),
                    $user
                )
            )
        );
    }

    public function approve(User $user): void
    {
        if ($this->status()->getValue() !== Status::SUBMITTED) {
            throw InvalidStatusTransitionException::approveOnlyFromSubmittedStatus();
        }

        if (!$user->isAdministrator()) {
            throw UnauthorizedUserException::shouldBeAdminToApprove($user);
        }

        $this->history = $this->history->transition(
            StatusTransition::new(
                Status::get(Status::APPROVED),
                $user
            )
        );
    }

    public function delete(User $user): void
    {
        if (!$user->isAdministrator()) {
            throw UnauthorizedUserException::shouldBeAdminToDelete($user);
        }

        $this->history = $this->history->transition(
            StatusTransition::new(
                Status::get(Status::DELETED),
                $user
            )
        );
    }

    public function status(): Status
    {
        return $this->history->currentStatus();
    }

    public function id(): ChocolateId
    {
        return $this->id;
    }

    public function lastTransitionUserId(): UserId
    {
        return $this->history->lastTransitionUserId();
    }

    public function producer(): Producer
    {
        return $this->producer;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function cacaoPercentage(): Percentage
    {
        return $this->cacaoPercentage;
    }

    public function wrapperType(): WrapperType
    {
        return $this->wrapperType;
    }

    public function quantity(): Quantity
    {
        return $this->quantity;
    }

    public function lastTransitionTime(): \DateTimeImmutable
    {
        return $this->history->lastTransitionTime();
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => (string) $this->id,
            'producer' => $this->producer->jsonSerialize(),
            'description' => $this->description,
            'percentage' => $this->cacaoPercentage->toInt(),
            'wrapperType' => $this->wrapperType->getValue(),
            'quantity' => $this->quantity->toInt(),
            'history' => $this->history->jsonSerialize()
        ];
    }
}
