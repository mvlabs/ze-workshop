<?php

declare(strict_types=1);

namespace App\Action;

use App\Domain\Service\ChocolatesServiceInterface;
use App\Infrastructure\Hydrators\ChocolatesCollection;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Hal\HalResponseFactory;
use Zend\Expressive\Hal\ResourceGenerator;

final class ChocolatesAction implements MiddlewareInterface
{
    /**
     * @var ChocolatesServiceInterface
     */
    private $chocolatesService;

    /**
     * @var ResourceGenerator
     */
    private $resourceGenerator;

    /**
     * @var HalResponseFactory
     */
    private $responseFactory;

    public function __construct(
        ChocolatesServiceInterface $chocolatesService,
        ResourceGenerator $resourceGenerator,
        HalResponseFactory $responseFactory
    ) {
        $this->chocolatesService = $chocolatesService;
        $this->resourceGenerator = $resourceGenerator;
        $this->responseFactory = $responseFactory;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $filters = $request->getQueryParams();

        $chocolates = $this->chocolatesService->getAll($filters);

        $resource = $this->resourceGenerator->fromObject(new ChocolatesCollection($chocolates), $request);

        return $this->responseFactory->createResponse($request, $resource);
    }
}
