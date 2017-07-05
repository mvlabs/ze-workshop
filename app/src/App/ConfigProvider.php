<?php

namespace App;
use App\Action\ChocolatesAction;
use App\Action\HomePageAction;
use App\Action\LoginAction;
use App\Action\LoginSubmitAction;
use App\Action\SubmitChocolatesAction;
use App\Action\UsersAction;
use App\Container\Action\ChocolatesServiceActionFactory;
use App\Container\Action\HomePageFactory;
use App\Container\Action\SubmitChocolatesActionFactory;
use App\Container\Action\TemplateActionFactory;
use App\Container\Action\LoginSubmitActionFactory;
use App\Container\Action\UsersServiceActionFactory;
use App\Container\Infrastructure\Repository\SqlRepositoryFactory;
use App\Container\Service\ChocolatesServiceFactory;
use App\Container\Service\UsersServiceFactory;
use App\Domain\Service\ChocolatesService;
use App\Domain\Service\UsersService;
use App\Infrastructure\Repository\Chocolates;
use App\Infrastructure\Repository\Users;
use Zend\ServiceManager\Factory\InvokableFactory;

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
                HomePageAction::class => TemplateActionFactory::class,
                ChocolatesAction::class => ChocolatesServiceActionFactory::class,
                SubmitChocolatesAction::class => TemplateActionFactory::class,
                LoginAction::class => TemplateActionFactory::class,
                LoginSubmitAction::class => LoginSubmitActionFactory::class,
                UsersAction::class => UsersServiceActionFactory::class,

                // SERVICES
                ChocolatesService::class => ChocolatesServiceFactory::class,
                UsersService::class => UsersServiceFactory::class,

                // REPOSITORIES
                Chocolates::class => SqlRepositoryFactory::class,
                Users::class => SqlRepositoryFactory::class,
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
