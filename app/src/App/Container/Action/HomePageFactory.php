<?php

namespace App\Container\Action;

use App\Action\HomePageAction;
use Interop\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class HomePageFactory
{
    public function __invoke(ContainerInterface $container): HomePageAction
    {
        return new HomePageAction($container->get(TemplateRendererInterface::class));
    }
}
