<?php

declare(strict_types=1);

namespace App\Container\Action;

use App\Domain\Service\UsersServiceInterface;
use Hal\HalResponseFactory;
use Hal\ResourceGenerator;
use Interop\Container\ContainerInterface;

final class UsersActionFactory
{
    public function __invoke(ContainerInterface $container, string $name)
    {
        return new $name(
            $container->get(UsersServiceInterface::class),
            $container->get(ResourceGenerator::class),
            $container->get(HalResponseFactory::class)
        );
    }
}
