<?php

declare(strict_types=1);

namespace App\Action;

use App\Domain\Service\ChocolatesService;
use App\Domain\Service\Exception\InvalidStatusTransitionException;
use App\Domain\Service\Exception\UserNotFoundException;
use App\Domain\Service\UsersService;
use App\Domain\Value\ChocolateId;
use App\Domain\Value\Exception\UnauthorizedUserException;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Middlewares\HttpAuthentication;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

final class DeleteChocolateAction implements MiddlewareInterface
{
    /**
     * @var ChocolatesService
     */
    private $chocolatesService;

    /**
     * @var UsersService
     */
    private $usersService;

    public function __construct(
        ChocolatesService $chocolatesService,
        UsersService $usersService
    ) {
        $this->chocolatesService = $chocolatesService;
        $this->usersService = $usersService;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $chocolateId = ChocolateId::fromString($request->getAttribute('id'));
        $chocolate = $this->chocolatesService->getChocolate($chocolateId);

        //try {
            $user = $this->usersService->byUsername($request->getAttribute(HttpAuthentication::class));

            $this->chocolatesService->delete($chocolate, $user);
        /*} catch (UserNotFoundException $e) {
            // TODO
        } catch (UnauthorizedUserException $e) {
            // TODO
        } catch (InvalidStatusTransitionException $e) {
            // TODO
        }*/

        return new JsonResponse([]);
    }
}
