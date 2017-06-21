<?php

declare(strict_types=1);

namespace App\Action;

use App\Domain\Service\ChocolatesService;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

final class ChocolatesAction implements MiddlewareInterface
{
    /**
     * @var ChocolatesService
     */
    private $chocolates;

    public function __construct(ChocolatesService $chocolates)
    {
        $this->chocolates = $chocolates;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        return new JsonResponse($this->chocolates->getAll());
    }
}
