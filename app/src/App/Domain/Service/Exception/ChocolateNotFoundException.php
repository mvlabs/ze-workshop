<?php

declare(strict_types=1);

namespace App\App\Domain\Service\Exception;

use App\Domain\Value\ChocolateId;

final class ChocolateNotFoundException extends \RuntimeException implements ExceptionInterface
{
    public static function fromChocolateId(ChocolateId $id)
    {
        return new self(sprintf('No chocolate was found for id %s', (string) $id));
    }
}
