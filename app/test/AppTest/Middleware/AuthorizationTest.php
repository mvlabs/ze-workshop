<?php

declare(strict_types=1);

namespace AppTest\Middleware;

use App\Domain\Entity\User;
use App\Domain\Service\Exception\UserNotFoundException;
use App\Domain\Service\UsersServiceInterface;
use App\Middleware\Authorization;
use Middlewares\HttpAuthentication;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Zend\Stratigility\Delegate\CallableDelegateDecorator;

final class AuthorizationTest extends TestCase
{
    /**
     * @var UsersServiceInterface
     */
    private $usersService;

    /**
     * @var Authorization
     */
    private $authorizationMiddleware;

    public function setUp()
    {
        $this->usersService = \Mockery::mock(UsersServiceInterface::class);
        $this->authorizationMiddleware = new Authorization($this->usersService);
    }

    public function testAuthorizedUserCanProceed()
    {
        $user = User::newAdministrator('gigi', 'zucon');
        $this->usersService->shouldReceive('getByUsername')->with('gigi')->andReturn($user);

        $request = (new ServerRequest())->withAttribute(HttpAuthentication::class, 'gigi');
        $response = new Response();
        self::assertSame(
            $response,
            $this->authorizationMiddleware->process(
                $request,
                new CallableDelegateDecorator(
                    function(ServerRequestInterface $request, ResponseInterface $response) {
                        return $response;
                    },
                    $response
                )
            )
        );
    }

    public function testNonAuthorizedUserReceivesA403()
    {
        $user = User::new('gigi', 'zucon');
        $this->usersService->shouldReceive('getByUsername')->with('gigi')->andReturn($user);

        $request = (new ServerRequest())->withAttribute(HttpAuthentication::class, 'gigi');
        $response = $this->authorizationMiddleware->process(
            $request,
            new CallableDelegateDecorator(
                function(ServerRequestInterface $request, ResponseInterface $response) {
                    return $response;
                },
                new Response()
            )
        );

        self::assertInstanceOf(Response\EmptyResponse::class, $response);
        self::assertSame(403, $response->getStatusCode());
    }

    public function testRequestWithNoUserReturnsA403()
    {
        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('No user was found for username gigi');

        $this->usersService
            ->shouldReceive('getByUsername')
            ->with('gigi')
            ->andThrow(UserNotFoundException::fromUsername('gigi'));

        $request = (new ServerRequest())->withAttribute(HttpAuthentication::class, 'gigi');
        $this->authorizationMiddleware->process(
            $request,
            new CallableDelegateDecorator(
                function(ServerRequestInterface $request, ResponseInterface $response) {
                    return $response;
                },
                new Response()
            )
        );
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}
