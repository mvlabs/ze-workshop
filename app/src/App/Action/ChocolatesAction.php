<?php

declare(strict_types=1);

namespace App\Action;

use App\Domain\Service\ChocolatesServiceInterface;
use App\Infrastructure\Hydrators\ChocolatesCollection;
use Hal\HalResponseFactory;
use Hal\ResourceGenerator;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

final class ChocolatesAction implements MiddlewareInterface
{
    /**
     * @var ChocolatesServiceInterface
     */
    private $chocolates;

    /**
     * @var ResourceGenerator
     */
    private $resourceGenerator;

    /**
     * @var HalResponseFactory
     */
    private $responseFactory;

    public function __construct(
        ChocolatesServiceInterface $chocolates,
        ResourceGenerator $resourceGenerator,
        HalResponseFactory $responseFactory
    ) {
        $this->chocolates = $chocolates;
        $this->resourceGenerator = $resourceGenerator;
        $this->responseFactory = $responseFactory;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $filters = $request->getQueryParams();

        $chocolates = $this->chocolates->getAll($filters);

        $resource = $this->resourceGenerator->fromObject(new ChocolatesCollection($chocolates), $request);

        return $this->responseFactory->createResponse($request, $resource);
    }
}
