<?php

declare(strict_types=1);

namespace App\Action;

use App\Domain\Service\ChocolatesService;
use App\Domain\Value\ChocolateId;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

final class ChocolateDetailsAction implements MiddlewareInterface
{
    /**
     * @var ChocolatesService
     */
    private $chocolatesService;

    public function __construct(ChocolatesService $chocolatesService)
    {
        $this->chocolatesService = $chocolatesService;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $chocolateId = ChocolateId::fromString($request->getAttribute('id'));

        $chocolate = $this->chocolatesService->getChocolate($chocolateId);

        return new JsonResponse($chocolate);
    }
}
