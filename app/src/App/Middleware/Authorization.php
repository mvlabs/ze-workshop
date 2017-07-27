<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Domain\Service\UsersService;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;

use Middlewares\HttpAuthentication;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\EmptyResponse;

final class Authorization implements MiddlewareInterface
{
    /**
     * @var UsersService
     */
    private $usersService;

    public function __construct(UsersService $usersService)
    {
        $this->usersService = $usersService;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $user = $this->usersService->byUsername($request->getAttribute(HttpAuthentication::class));

        if (! $user->isAdministrator()) {
            return new EmptyResponse(403);
        }

        return $delegate->process($request);
    }
}