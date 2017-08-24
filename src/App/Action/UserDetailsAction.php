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
use Zend\Expressive\Hal\HalResponseFactory;
use Zend\Expressive\Hal\ResourceGenerator;

final class UserDetailsAction implements MiddlewareInterface
{
    /**
     * @var UsersServiceInterface
     */
    private $usersService;

    /**
     * @var ResourceGenerator
     */
    private $resourceGenerator;

    /**
     * @var HalResponseFactory
     */
    private $responseFactory;

    public function __construct(
        UsersServiceInterface $usersService,
        ResourceGenerator $resourceGenerator,
        HalResponseFactory $responseFactory
    ) {
        $this->usersService = $usersService;
        $this->resourceGenerator = $resourceGenerator;
        $this->responseFactory = $responseFactory;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $userId = UserId::fromString($request->getAttribute('id'));

        $user = $this->usersService->getById($userId);

        $resource = $this->resourceGenerator->fromObject($user, $request);

        return $this->responseFactory->createResponse($request, $resource);
    }
}
