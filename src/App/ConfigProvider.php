<?php

namespace App;

use App\Action\ApproveChocolateAction;
use App\Action\ChocolateDetailsAction;
use App\Action\ChocolatesAction;
use App\Action\DeleteChocolateAction;
use App\Action\SubmitChocolateAction;
use App\Action\TokenAction;
use App\Action\UserDetailsAction;
use App\Action\UsersAction;
use App\Container\Action\ChocolatesActionFactory;
use App\Container\Action\ChocolatesAndUsersActionFactory;
use App\Container\Action\TokenActionFactory;
use App\Container\Action\UsersServiceActionFactory;
use App\Container\Domain\Service\ChocolatesServiceFactory;
use App\Container\Domain\Service\UsersServiceFactory;
use App\Container\Infrastructure\Hydrators\HydratorPluginManagerDelegatorFactory;
use App\Container\Infrastructure\Repository\SqlRepositoryFactory;
use App\Domain\Entity\Chocolate;
use App\Domain\Service\ChocolatesServiceInterface;
use App\Domain\Service\UsersServiceInterface;
use App\Infrastructure\Hydrators\ChocolateExtractor;
use App\Infrastructure\Hydrators\ChocolatesCollection;
use App\Infrastructure\Repository\Chocolates;
use App\Infrastructure\Repository\Users;
use Zend\Expressive\Hal\Metadata\MetadataMap;
use Zend\Expressive\Hal\Metadata\RouteBasedCollectionMetadata;
use Zend\Expressive\Hal\Metadata\RouteBasedResourceMetadata;
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
    public function __invoke()
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
            MetadataMap::class => $this->getMetadata(),
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
                ChocolatesAction::class => ChocolatesActionFactory::class,
                ChocolateDetailsAction::class => ChocolatesActionFactory::class,
                UsersAction::class => UsersServiceActionFactory::class,
                UserDetailsAction::class => UsersServiceActionFactory::class,
                SubmitChocolateAction::class => ChocolatesAndUsersActionFactory::class,
                ApproveChocolateAction::class => ChocolatesAndUsersActionFactory::class,
                DeleteChocolateAction::class => ChocolatesAndUsersActionFactory::class,
                TokenAction::class => TokenActionFactory::class,

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

    private function getMetadata()
    {
        return [
            [
                '__class__' => RouteBasedResourceMetadata::class,
                'resource_class' => Chocolate::class,
                'route' => 'chocolate-details',
                'extractor' => ChocolateExtractor::class,
            ],
            [
                '__class__' => RouteBasedCollectionMetadata::class,
                'collection_class' => ChocolatesCollection::class,
                'collection_relation' => 'chocolate_details',
                'route' => 'chocolates',
            ]
        ];
    }
}
