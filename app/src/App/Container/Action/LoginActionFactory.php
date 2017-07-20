<?php

declare(strict_types=1);

namespace App\Container\Action;

use App\Action\LoginAction;
use App\Domain\Service\UsersService;
use Interop\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;

final class LoginActionFactory
{
    public function __invoke(ContainerInterface $container): LoginAction
    {
        return new LoginAction(
            $container->get(UsersService::class),
            $container->get(RouterInterface::class)
        );
    }
}
