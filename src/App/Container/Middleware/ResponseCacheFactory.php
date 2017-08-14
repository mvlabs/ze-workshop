<?php

declare(strict_types=1);

namespace App\Container\Middleware;

use Interop\Container\ContainerInterface;
use App\Middleware\ResponseCache;

final class ResponseCacheFactory
{
    public function __invoke(ContainerInterface $container): ResponseCache
    {
        $cacheFilesPath = $container->get('config')['response-cache']['path'];

        return new ResponseCache($cacheFilesPath);
    }
}
