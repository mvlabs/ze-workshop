<?php

declare(strict_types=1);

namespace App\Action;

use App\Domain\Service\UsersService;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Router\RouterInterface;

final class LoginSubmitAction implements MiddlewareInterface
{
    /**
     * @var UsersService
     */
    private $usersService;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        UsersService $usersService,
        RouterInterface $router
    ) {
        $this->usersService = $usersService;
        $this->router = $router;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $body = $request->getParsedBody();

        $this->usersService->register(
            $body['name'],
            $body['surname']
        );

        return new RedirectResponse($this->router->generateUri('users'));
    }
}