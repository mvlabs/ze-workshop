<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\App\Domain\Entity\Exception\InvalidHistoryException;
use App\App\Domain\Entity\User;
use App\App\Domain\Value\ChocolateHistory;
use App\App\Domain\Value\Exception\InvalidStatusTransitionException;
use App\App\Domain\Value\Exception\UnauthorizedUserException;
use App\App\Domain\Value\Percentage;
use App\App\Domain\Value\Producer;
use App\App\Domain\Value\Quantity;
use App\App\Domain\Value\Status;
use App\App\Domain\Value\StatusTransition;
use App\App\Domain\Value\UserId;
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
     * @var ChocolateHistory
     */
    private $history;

    /**
     * @throws InvalidHistoryException
     */
    private function __construct(
        ChocolateId $id,
        Producer $producer,
        string $description,
        Percentage $cacaoPercentage,
        WrapperType $wrapperType,
        Quantity $quantity,
        ChocolateHistory $history
    ) {
        if ($history->isEmpty()) {
            throw InvalidHistoryException::fromEmptyHistory();
        }

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
            Quantity::integer($quantity),
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
}
