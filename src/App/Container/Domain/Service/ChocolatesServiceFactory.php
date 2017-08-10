<?php

declare(strict_types=1);

namespace App\Container\Domain\Service;

use App\Domain\Service\ChocolatesService;
use App\Infrastructure\Repository\Chocolates;
use Interop\Container\ContainerInterface;

final class ChocolatesServiceFactory
{
    public function __invoke(ContainerInterface $container): ChocolatesService
    {
        return new ChocolatesService($container->get(Chocolates::class));
    }
}
