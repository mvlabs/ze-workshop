<?php

declare(strict_types=1);

namespace App\Container\Domain\Service;

use App\Domain\Service\UsersService;
use App\Infrastructure\Repository\Users;
use Interop\Container\ContainerInterface;

final class UsersServiceFactory
{
    public function __invoke(ContainerInterface $container): UsersService
    {
        return new UsersService($container->get(Users::class));
    }
}
