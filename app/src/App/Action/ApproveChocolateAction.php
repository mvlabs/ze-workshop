<?php

declare(strict_types=1);

namespace App\Action;

use App\Domain\Service\ChocolatesService;
use App\Domain\Service\UsersService;
use App\Domain\Value\ChocolateId;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Middlewares\HttpAuthentication;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

final class ApproveChocolateAction implements MiddlewareInterface
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

            $this->chocolatesService->approve($chocolate, $user);
        /*} catch (UserNotFoundException $e) {
            // TODO
        } catch (InvalidStatusTransitionException $e) {
            // TODO
        } catch (UnauthorizedUserException $e) {
            // TODO
        } catch (\App\Domain\Service\Exception\InvalidStatusTransitionException $e) {
            // TODO
        } catch (\Throwable $e) {
            // TODO
        }*/

        return new JsonResponse([]);
    }
}
