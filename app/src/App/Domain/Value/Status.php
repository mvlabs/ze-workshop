<?php

declare(strict_types=1);

namespace App\App\Domain\Value;

use MabeEnum\Enum;

final class Status extends Enum
{
    const SUBMITTED = 'submitted';
    const APPROVED = 'approved';
    const DELETED = 'deleted';
}
