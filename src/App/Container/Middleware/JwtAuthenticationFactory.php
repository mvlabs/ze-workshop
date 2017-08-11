<?php

declare(strict_types=1);

namespace App\Container\Middleware;

use Interop\Container\ContainerInterface;
use Slim\Middleware\JwtAuthentication;

final class JwtAuthenticationFactory
{
    public function __invoke(ContainerInterface $container): JwtAuthentication
    {
        return new JwtAuthentication([
            'secret' => $container->get('config')['jwt']['secret'],
            'secure' => false,
            'attribute' => JwtAuthentication::class
        ]);
    }
}
