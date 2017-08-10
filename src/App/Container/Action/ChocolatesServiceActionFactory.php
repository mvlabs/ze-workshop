<?php

declare(strict_types=1);

namespace App\Container\Action;

use App\Domain\Service\ChocolatesServiceInterface;
use Interop\Container\ContainerInterface;

final class ChocolatesServiceActionFactory
{
    /**
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, string $name)
    {
        return new $name(
            $container->get(ChocolatesServiceInterface::class)
        );
    }
}
