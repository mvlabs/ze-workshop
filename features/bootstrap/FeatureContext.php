<?php

declare(strict_types=1);

use App\Domain\Entity\User;
use App\Domain\Service\Exception\UserNotFoundException;
use App\Domain\Service\UsersServiceInterface;
use App\Middleware\Authorization;
use Behat\Behat\Context\Context;
use PHPUnit\Framework\Assert;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Middleware\JwtAuthentication;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Zend\Stratigility\Delegate\CallableDelegateDecorator;

class FeatureContext implements Context
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var UsersServiceInterface
     */
    private $usersService;

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var \Exception|null
     */
    private $exception = null;

    /** @BeforeScenario */
    public function beforeScenario()
    {
        $this->usersService = \Mockery::mock(UsersServiceInterface::class);
    }

    /**
     * @Given I am an administrator user
     */
    public function iAmAnAdministratorUser()
    {
        $this->user = User::newAdministrator('gigi', 'zucon');

        $this->usersService->shouldReceive('getByUsername')->with('gigi')->andReturn($this->user);
    }

    /**
     * @When I try to access administration functionalities
     */
    public function iTryToAccessAdministrationFunctionalities()
    {
        $authorizationMiddleware = new Authorization($this->usersService);

        $request = (new ServerRequest())->withAttribute(
            JwtAuthentication::class,
            json_decode(json_encode([
                'data' => [
                    'username' => $this->user->username()
                ]
            ]))
        );

        try {
            $this->response = $authorizationMiddleware->process(
                $request,
                new CallableDelegateDecorator(
                    function (ServerRequestInterface $request, ResponseInterface $response) {
                        return $response;
                    },
                    new Response()
                )
            );
        } catch (\Exception $e) {
            $this->exception = $e;
        }
    }

    /**
     * @Then I am granted access
     */
    public function iAmGrantedAccess()
    {
        Assert::assertSame(200, $this->response->getStatusCode());
    }

    /**
     * @Given I am a non administrator user
     */
    public function iAmANonAdministratorUser()
    {
        $this->user = User::new('gigi', 'zucon');

        $this->usersService->shouldReceive('getByUsername')->with('gigi')->andReturn($this->user);
    }

    /**
     * @Then I am denied access
     */
    public function iAmDeniedAccess()
    {
        Assert::assertTrue(
            $this->exception instanceof UserNotFoundException ||
            403 === $this->response->getStatusCode()
        );
    }

    /**
     * @Given I am a unknown user
     */
    public function iAmAUnknownUser()
    {
        $this->user = User::new('gigi', 'zucon');

        $this->usersService
            ->shouldReceive('getByUsername')
            ->with('gigi')
            ->andThrow(UserNotFoundException::fromUsername('gigi'));
    }

    /** @AfterScenario */
    public function afterScenario()
    {
        Mockery::close();
    }
}
