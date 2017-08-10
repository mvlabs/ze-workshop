<?php

declare(strict_types=1);

namespace App\Container\Action;

use App\Action\UsersAction;
use App\Domain\Service\UsersServiceInterface;
use Interop\Container\ContainerInterface;

class UsersActionFactory
{
    public function __invoke(ContainerInterface $container): UsersAction
    {
        return new UsersAction(
            $container->get(UsersServiceInterface::class)
        );
    }
}