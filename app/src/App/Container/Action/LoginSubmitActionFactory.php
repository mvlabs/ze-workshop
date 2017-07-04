<?php

declare(strict_types=1);

namespace App\Container\Action;

use App\Action\LoginSubmitAction;
use App\Domain\Service\UsersService;
use Interop\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;

final class LoginSubmitActionFactory
{
    public function __invoke(ContainerInterface $container): LoginSubmitAction
    {
        return new LoginSubmitAction(
            $container->get(UsersService::class),
            $container->get(RouterInterface::class)
        );
    }
}
