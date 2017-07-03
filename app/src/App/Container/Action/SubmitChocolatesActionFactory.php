<?php

declare(strict_types=1);

namespace App\Container\Action;

use App\Action\SubmitChocolatesAction;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

final class SubmitChocolatesActionFactory
{
    public function __invoke(ContainerInterface $container): SubmitChocolatesAction
    {
        return new SubmitChocolatesAction($container->get(TemplateRendererInterface::class));
    }
}
