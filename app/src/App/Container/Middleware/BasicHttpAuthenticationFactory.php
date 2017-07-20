<?php

declare(strict_types=1);

namespace App\Container\Middleware;

use App\Domain\Entity\User;
use App\Domain\Service\UsersService;
use Interop\Container\ContainerInterface;
use Middlewares\BasicAuthentication;
use Middlewares\HttpAuthentication;

final class BasicHttpAuthenticationFactory
{
    public function __invoke(ContainerInterface $container): HttpAuthentication
    {
        /** @var UsersService $users */
        $users = $container->get(UsersService::class);

        $credentials = array_reduce(
            $users->getAll(),
            function (array $carry, User $user) {
                $username = $user->username() . '---' . $user->password();
                $password = base64_encode($username);
                $carry[$user->username()] = $user->password();
                return $carry;
            },
            []
        );

        return (new BasicAuthentication($credentials))->attribute(HttpAuthentication::class);
    }
}
