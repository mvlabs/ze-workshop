<?php

namespace App;
use App\Action\ChocolatesAction;
use App\Action\HomePageAction;
use App\Container\Action\ChocolatesActionFactory;
use App\Container\Action\HomePageFactory;
use App\Container\Infrastructure\Repository\SqlChocolatesFactory;
use App\Container\Service\ChocolatesServiceFactory;
use App\Domain\Service\ChocolatesService;
use App\Infrastructure\Repository\Chocolates;

/**
 * The configuration provider for the App module
 *
 * @see https://docs.zendframework.com/zend-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     *
     * @return array
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
        ];
    }

    /**
     * Returns the container dependencies
     *
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            'factories'  => [
                // ACTIONS
                HomePageAction::class => HomePageFactory::class,
                ChocolatesAction::class => ChocolatesActionFactory::class,

                // SERVICES
                ChocolatesService::class => ChocolatesServiceFactory::class,

                // REPOSITORIES
                Chocolates::class => SqlChocolatesFactory::class,
            ],
        ];
    }

    /**
     * Returns the templates configuration
     *
     * @return array
     */
    public function getTemplates(): array
    {
        return [
            'paths' => [
                'app'    => ['templates/app'],
                'error'  => ['templates/error'],
                'layout' => ['templates/layout'],
            ],
        ];
    }
}
