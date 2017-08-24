<?php

declare(strict_types=1);

namespace App\Container\Action;

use App\Domain\Service\UsersServiceInterface;
use Interop\Container\ContainerInterface;
use Zend\Expressive\Hal\HalResponseFactory;
use Zend\Expressive\Hal\ResourceGenerator;

final class UsersActionFactory
{
    public function __invoke(ContainerInterface $container, string $name)
    {
        return new $name(
	        $container->get(UsersServiceInterface::class),
            $container->get(ResourceGenerator::class),
            $container->get(HalResponseFactory::class)
        );
    }
}
