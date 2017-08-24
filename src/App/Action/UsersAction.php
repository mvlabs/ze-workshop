<?php

declare(strict_types=1);

namespace App\Action;

use App\Domain\Service\UsersServiceInterface;
use App\Infrastructure\Hydrators\ChocolatesCollection;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Hal\HalResponseFactory;
use Zend\Expressive\Hal\ResourceGenerator;

final class UsersAction implements MiddlewareInterface
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
        $users = $this->usersService->getAll();

        $resource = $this->resourceGenerator->fromObject(new ChocolatesCollection($users), $request);

        return $this->responseFactory->createResponse($request, $resource);
    }
}
