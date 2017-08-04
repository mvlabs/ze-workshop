<?php

declare(strict_types=1);

namespace App\Container\Action;

use App\Action\TokenAction;
use App\Domain\Service\UsersServiceInterface;
use Interop\Container\ContainerInterface;

final class TokenActionFactory
{
    public function __invoke(ContainerInterface $container): TokenAction
    {
        return new TokenAction(
            $container->get(UsersServiceInterface::class),
            $container->get('config')['jwt']['secret']
        );
    }
}
