<?php

declare(strict_types=1);

namespace App\App\Domain\Value;

use MabeEnum\Enum;

final class WrapperType extends Enum
{
    const PAPER = 'paper';
    const BOX = 'box';
    const BAND = 'band';
}
