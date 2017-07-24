<?php

declare(strict_types=1);

namespace App\Container\Action;

use App\Action\SubmitChocolatesAction;
use App\Domain\Service\ChocolatesService;
use App\Domain\Service\UsersService;
use Interop\Container\ContainerInterface;

final class ChocolatesAndUsersActionFactory
{
    public function __invoke(ContainerInterface $container, string $name): SubmitChocolatesAction
    {
        return new SubmitChocolatesAction(
            $container->get(ChocolatesService::class),
            $container->get(UsersService::class)
        );
    }
}
