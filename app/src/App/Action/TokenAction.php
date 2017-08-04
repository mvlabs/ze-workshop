<?php

declare(strict_types=1);

namespace App\Action;

use App\Domain\Service\Exception\UserNotFoundException;
use App\Domain\Service\UsersServiceInterface;
use Firebase\JWT\JWT;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tuupola\Base62;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Response\JsonResponse;

final class TokenAction implements MiddlewareInterface
{
    /**
     * @var UsersServiceInterface
     */
    private $usersService;

    /**
     * @var string
     */
    private $jwtSecret;

    public function __construct(
        UsersServiceInterface $usersService,
        string $jwtSecret
    ) {
        $this->usersService = $usersService;
        $this->jwtSecret = $jwtSecret;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $data = $request->getParsedBody();

        if (!isset($data['username'])) {
            return new EmptyResponse(401);
        }

        try {
            $user = $this->usersService->byUsername($data['username']);
        } catch (UserNotFoundException $exception) {
            return new EmptyResponse(401);
        }

        if ($user->password() !== $data['password']) {
            return new EmptyResponse(401);
        }

        $future = new \DateTime("+10 minutes");

        $payload = [
            'iat' => (new \DateTime())->getTimestamp(),
            'exp' => $future->getTimestamp(),
            'jti' => (new Base62)->encode(random_bytes(16)),
            'data' => [
                'id' => (string) $user->id(),
                'username' => $user->username()
            ]
        ];

        $token = JWT::encode($payload, $this->jwtSecret, 'HS256');

        return new JsonResponse([
            'token' => $token,
            'expires' => $future->getTimestamp()
        ]);
    }
}
