<?php

declare(strict_types=1);

namespace App\Container\Infrastructure\Hydrators;

use App\Infrastructure\Hydrators\SerializeExtractor;
use Interop\Container\ContainerInterface;
use Zend\Hydrator\HydratorPluginManager;
use Zend\ServiceManager\Factory\InvokableFactory;

final class HydratorPluginManagerDelegatorFactory
{
    public function __invoke(
        ContainerInterface $container,
        string $name,
        callable $callback
    ): HydratorPluginManager
    {
        /** @var HydratorPluginManager $hydratorContainer */
        $hydratorContainer = $callback();

        $hydratorContainer->setFactory(
            SerializeExtractor::class,
            InvokableFactory::class
        );

        return $hydratorContainer;
    }
}
