<?php

declare(strict_types=1);

namespace App\Action;

use App\Domain\Service\UsersServiceInterface;
use App\Domain\Value\UserId;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

final class UserDetailsAction implements MiddlewareInterface
{
    /**
     * @var UsersServiceInterface
     */
    private $usersService;

    public function __construct(UsersServiceInterface $usersService)
    {
        $this->usersService = $usersService;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $userId = UserId::fromString($request->getAttribute('id'));

        $user = $this->usersService->getById($userId);

        return new JsonResponse($user);
    }
}
