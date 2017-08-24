<?php

declare(strict_types=1);

namespace App\Action;

use App\Domain\Service\ChocolatesServiceInterface;
use App\Domain\Value\ChocolateId;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Hal\HalResponseFactory;
use Zend\Expressive\Hal\ResourceGenerator;

final class ChocolateDetailsAction implements MiddlewareInterface
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
        $chocolateId = ChocolateId::fromString($request->getAttribute('id'));

        $chocolate = $this->chocolatesService->getChocolate($chocolateId);

        $resource = $this->resourceGenerator->fromObject($chocolate, $request);

        return $this->responseFactory->createResponse($request, $resource);
    }
}
