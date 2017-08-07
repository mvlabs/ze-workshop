<?php

declare(strict_types=1);

namespace App\Container\Action;

use App\Domain\Service\ChocolatesServiceInterface;
use Hal\HalResponseFactory;
use Hal\ResourceGenerator;
use Interop\Container\ContainerInterface;

final class ChocolatesActionFactory
{
    /**
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, string $name)
    {
        return new $name(
            $container->get(ChocolatesServiceInterface::class),
            $container->get(ResourceGenerator::class),
            $container->get(HalResponseFactory::class)
        );
    }
}
