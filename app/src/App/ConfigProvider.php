<?php

namespace App;

use App\Action\ApproveChocolateAction;
use App\Action\ChocolateDetailsAction;
use App\Action\ChocolatesAction;
use App\Action\DeleteChocolateAction;
use App\Action\HomePageAction;
use App\Action\LoginAction;
use App\Action\SubmitChocolatesAction;
use App\Action\UserDetailsAction;
use App\Action\UsersAction;
use App\Action\ViewLoginAction;
use App\Action\ViewSubmitChocolatesAction;
use App\Container\Action\ChocolateDetailsActionFactory;
use App\Container\Action\ChocolatesActionFactory;
use App\Container\Action\ChocolatesAndUsersActionFactory;
use App\Container\Action\LoginActionFactory;
use App\Container\Action\TemplateActionFactory;
use App\Container\Action\UsersActionFactory;
use App\Container\Action\UsersServiceActionFactory;
use App\Container\Infrastructure\Hydrators\HydratorPluginManagerDelegatorFactory;
use App\Container\Infrastructure\Repository\SqlRepositoryFactory;
use App\Container\Middleware\AuthorizationFactory;
use App\Container\Middleware\BasicHttpAuthenticationFactory;
use App\Container\Service\ChocolatesServiceFactory;
use App\Container\Service\UsersServiceFactory;
use App\Domain\Entity\Chocolate;
use App\Domain\Entity\User;
use App\Domain\Service\ChocolatesServiceInterface;
use App\Domain\Service\UsersServiceInterface;
use App\Infrastructure\Hydrators\ChocolatesCollection;
use App\Infrastructure\Hydrators\SerializeExtractor;
use App\Infrastructure\Hydrators\UserExtractor;
use App\Infrastructure\Hydrators\UsersCollection;
use App\Infrastructure\Repository\Chocolates;
use App\Infrastructure\Repository\Users;
use App\Middleware\Authorization;
use Hal\Metadata\MetadataMap;
use Hal\Metadata\RouteBasedCollectionMetadata;
use Hal\Metadata\RouteBasedResourceMetadata;
use Middlewares\HttpAuthentication;
use Zend\Hydrator\HydratorPluginManager;

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
            'templates' => $this->getTemplates(),
            MetadataMap::class => $this->getMetadata(),
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
                ChocolatesAction::class => ChocolatesActionFactory::class,
                ChocolateDetailsAction::class => ChocolatesActionFactory::class,
                ViewSubmitChocolatesAction::class => TemplateActionFactory::class,
                ViewLoginAction::class => TemplateActionFactory::class,
                LoginAction::class => LoginActionFactory::class,
                UsersAction::class => UsersActionFactory::class,
                UserDetailsAction::class => UsersActionFactory::class,
                SubmitChocolatesAction::class => ChocolatesAndUsersActionFactory::class,
                ApproveChocolateAction::class => ChocolatesAndUsersActionFactory::class,
                DeleteChocolateAction::class => ChocolatesAndUsersActionFactory::class,

                // MIDDLEWARE
                HttpAuthentication::class => BasicHttpAuthenticationFactory::class,
                Authorization::class => AuthorizationFactory::class,

                // SERVICES
                ChocolatesServiceInterface::class => ChocolatesServiceFactory::class,
                UsersServiceInterface::class => UsersServiceFactory::class,

                // REPOSITORIES
                Chocolates::class => SqlRepositoryFactory::class,
                Users::class => SqlRepositoryFactory::class,
            ],
            'delegators' => [
                HydratorPluginManager::class => [
                    HydratorPluginManagerDelegatorFactory::class
                ]
            ]
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

    public function getMetadata(): array
    {
        return [
            [
                '__class__' => RouteBasedResourceMetadata::class,
                'resource_class' => Chocolate::class,
                'route' => 'chocolate-details',
                'extractor' => SerializeExtractor::class,
            ],
            [
                '__class__' => RouteBasedCollectionMetadata::class,
                'collection_class' => ChocolatesCollection::class,
                'collection_relation' => 'chocolate_details',
                'route' => 'chocolates',
            ],
            [
                '__class__' => RouteBasedResourceMetadata::class,
                'resource_class' => User::class,
                'route' => 'user-details',
                'extractor' => SerializeExtractor::class,
            ],
            [
                '__class__' => RouteBasedCollectionMetadata::class,
                'collection_class' => UsersCollection::class,
                'collection_relation' => 'user_details',
                'route' => 'users',
            ]
        ];
    }
}
