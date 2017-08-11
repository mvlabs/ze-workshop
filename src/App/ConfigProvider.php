<?php

namespace App;
use App\Action\ApproveChocolateAction;
use App\Action\ChocolateDetailsAction;
use App\Action\ChocolatesAction;
use App\Action\SubmitChocolateAction;
use App\Action\UserDetailsAction;
use App\Action\UsersAction;
use App\Container\Action\ChocolatesAndUsersActionFactory;
use App\Container\Action\ChocolatesServiceActionFactory;
use App\Container\Action\UsersActionFactory;
use App\Container\Action\UsersServiceActionFactory;
use App\Container\Domain\Service\ChocolatesServiceFactory;
use App\Container\Domain\Service\UsersServiceFactory;
use App\Container\Infrastructure\Repository\SqlRepositoryFactory;
use App\Container\Middleware\ResponseCacheFactory;
use App\Domain\Service\ChocolatesServiceInterface;
use App\Domain\Service\UsersServiceInterface;
use App\Infrastructure\Repository\Chocolates;
use App\Infrastructure\Repository\Users;
use App\Middleware\ResponseCache;

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
    public function __invoke()
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
    public function getDependencies()
    {
        return [
            'factories'  => [
                // ACTIONS
                ChocolatesAction::class => ChocolatesServiceActionFactory::class,
                ChocolateDetailsAction::class => ChocolatesServiceActionFactory::class,
                UsersAction::class => UsersServiceActionFactory::class,
                UserDetailsAction::class => UsersServiceActionFactory::class,
                SubmitChocolateAction::class => ChocolatesAndUsersActionFactory::class,
                ApproveChocolateAction::class => ChocolatesAndUsersActionFactory::class,

                // SERVICES
                ChocolatesServiceInterface::class => ChocolatesServiceFactory::class,
                UsersServiceInterface::class => UsersServiceFactory::class,

                // MIDDLEWARE
                ResponseCache::class => ResponseCacheFactory::class,

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
    public function getTemplates()
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
