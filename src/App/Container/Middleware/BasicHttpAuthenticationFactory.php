<?php

declare(strict_types=1);

namespace App\Container\Middleware;

use App\Domain\Entity\User;
use App\Domain\Service\UsersServiceInterface;
use Interop\Container\ContainerInterface;
use Middlewares\BasicAuthentication;
use Middlewares\HttpAuthentication;

final class BasicHttpAuthenticationFactory
{
    public function __invoke(ContainerInterface $container): HttpAuthentication
    {
        /** @var UsersServiceInterface $users */
        $users = $container->get(UsersServiceInterface::class);

        $credentials = array_reduce(
            $users->getAll(),
            function (array $carry, User $user) {
                $carry[$user->username()] = $user->password();
                return $carry;
            },
            []
        );

        return (new BasicAuthentication($credentials))->attribute(HttpAuthentication::class);
    }
}
