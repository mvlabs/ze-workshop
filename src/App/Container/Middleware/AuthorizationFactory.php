<?php

declare(strict_types=1);

namespace App\Container\Middleware;

use App\Domain\Service\UsersServiceInterface;
use Interop\Container\ContainerInterface;

final class AuthorizationFactory
{
    public function __invoke(ContainerInterface $container, string $name)
    {
        return new $name(
            $container->get(UsersServiceInterface::class)
        );
    }
}
