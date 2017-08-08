<?php

declare(strict_types=1);

namespace App\Container\Middleware;

use Interop\Container\ContainerInterface;
use Middlewares\AccessLog;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

final class AccessLogFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $logger = new Logger('access');
        $logger->pushHandler(new StreamHandler(__DIR__ . '/../../../../data/access-log.txt'));

        $accessLog = new AccessLog($logger);

        $accessLog->format('%v %h %u %t "%r" %>s %b "%{Referer}i" -> %U "%{User-Agent}i" ClientIp: %a Duration: %{us}T');
        $accessLog->ipAttribute('client-ip');

        return $accessLog;
    }
}
