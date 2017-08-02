<?php

declare(strict_types=1);

namespace App\Container\Action;

use App\Action\LoginAction;
use App\Domain\Service\UsersServiceInterface;
use Interop\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;

final class LoginActionFactory
{
    public function __invoke(ContainerInterface $container): LoginAction
    {
        return new LoginAction(
            $container->get(UsersServiceInterface::class),
            $container->get(RouterInterface::class)
        );
    }
}
