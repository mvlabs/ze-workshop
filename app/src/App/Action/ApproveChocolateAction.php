<?php

declare(strict_types=1);

namespace App\Action;

use App\Domain\Service\ChocolatesServiceInterface;
use App\Domain\Service\UsersServiceInterface;
use App\Domain\Value\ChocolateId;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Middlewares\HttpAuthentication;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Middleware\JwtAuthentication;
use Zend\Diactoros\Response\JsonResponse;

final class ApproveChocolateAction implements MiddlewareInterface
{
    /**
     * @var ChocolatesServiceInterface
     */
    private $chocolatesService;

    /**
     * @var UsersServiceInterface
     */
    private $usersService;

    public function __construct(
        ChocolatesServiceInterface $chocolatesService,
        UsersServiceInterface $usersService
    ) {
        $this->chocolatesService = $chocolatesService;
        $this->usersService = $usersService;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $chocolateId = ChocolateId::fromString($request->getAttribute('id'));
        $chocolate = $this->chocolatesService->getChocolate($chocolateId);

        //$username = $request->getAttribute(HttpAuthentication::class);
        $username = $request->getAttribute(JwtAuthentication::class)->data->username;

        $user = $this->usersService->byUsername($username);

        $this->chocolatesService->approve($chocolate, $user);

        return new JsonResponse([]);
    }
}
