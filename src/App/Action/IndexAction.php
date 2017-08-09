<?php

declare(strict_types=1);

namespace App\Action;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

final class IndexAction implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        return new JsonResponse([
            'welcome to' => 'Web Summer Camp'
        ]);
    }
}