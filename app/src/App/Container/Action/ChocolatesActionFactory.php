<?php

declare(strict_types=1);

namespace App\Container\Action;

use App\Action\ChocolatesAction;
use App\Domain\Service\ChocolatesService;
use Interop\Container\ContainerInterface;

final class ChocolatesActionFactory
{
    public function __invoke(ContainerInterface $container): ChocolatesAction
    {
        return new ChocolatesAction(
            $container->get(ChocolatesService::class)
        );
    }
}
