<?php

declare(strict_types=1);

namespace App\Container\Service;

use App\Domain\Service\ChocolatesService;
use App\Infrastructure\Repository\Chocolates;
use Doctrine\DBAL\Connection;
use Interop\Container\ContainerInterface;

final class ChocolatesServiceFactory
{
    public function __invoke(ContainerInterface $container): ChocolatesService
    {
        return new ChocolatesService($container->get(Chocolates::class));
    }
}
