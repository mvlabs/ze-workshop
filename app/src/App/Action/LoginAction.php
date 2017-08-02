<?php

declare(strict_types=1);

namespace App\Action;

use App\Domain\Service\UsersServiceInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Router\RouterInterface;

final class LoginAction implements MiddlewareInterface
{
    /**
     * @var UsersServiceInterface
     */
    private $usersService;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        UsersServiceInterface $usersService,
        RouterInterface $router
    ) {
        $this->usersService = $usersService;
        $this->router = $router;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $body = $request->getParsedBody();

        if (empty($body['username']) || empty($body['password'])) {
            return new JsonResponse([], 400);
        }

        $this->usersService->register(
            $body['username'],
            $body['password']
        );

        return new JsonResponse([]);
    }
}
