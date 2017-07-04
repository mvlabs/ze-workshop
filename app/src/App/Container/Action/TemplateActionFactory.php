<?php

declare(strict_types=1);

namespace App\Container\Action;

use Interop\Container\ContainerInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

final class TemplateActionFactory
{
    public function __invoke(
        ContainerInterface $container,
        string $name
    ): MiddlewareInterface
    {
        return new $name($container->get(TemplateRendererInterface::class));
    }
}
