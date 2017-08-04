<?php

declare(strict_types=1);

namespace AppTest\Domain\Service;

use App\Domain\Entity\User;
use App\Domain\Service\UsersService;
use App\Infrastructure\Repository\Users;
use App\Domain\Value\UserId;
use App\Domain\Service\Exception\UserNotFoundException;
use Mockery;
use PHPUnit\Framework\TestCase;

final class UsersServiceTest extends TestCase
{
    private $users;

    /**
     * @var UsersService
     */
    private $service;

    public function setUp(): void
    {
        $this->users = Mockery::mock(Users::class);
        $this->service = new UsersService($this->users);
    }

    public function testRegister(): void
    {
        $username = 'gigi';
        $password = 'zucon';

        $this->users->shouldReceive('add')->with(\Mockery::on(
            function (User $user) use ($username, $password) {
                return $user->username() === $username &&
                    $user->password() === $password;
            }
        ));

        $this->service->register($username, $password);
    }

    public function testAll(): void
    {
     
        $this->users->shouldReceive('all');

        $this->service->getAll();

    }

    public function testGetById(): void
    {
        $user = User::new('gigi', 'zucon');

        $this->users->shouldReceive('findById')->with($user->id())->andReturn($user);

        self::assertSame($user, $this->service->getById($user->id()));
    }

    public function testGetByIdLookupFailure(): void
    {
        
        $id = UserId::new();
        $this->users->shouldReceive('findById')->with($id)->andReturn(null);

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('No user was found for id');

        $this->service->getById($id);
    }

    public function testGetByUsername(): void
    {
        $user = User::new('gigi', 'zucon');

        $this->users->shouldReceive('findByUsername')->with($user->username())->andReturn($user);

        self::assertSame($user, $this->service->getByUsername($user->username()));
    }

    public function testGetByUsernameLookupFailure(): void
    {

        $username = 'gianfranco';

        $this->users->shouldReceive('findByUsername')->with($username)->andReturn(null);

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('No user was found for username');

        $this->service->getByUsername($username);
    }

    protected function assertPostConditions(): void
    {
        $container = Mockery::getContainer();
        if (null !== $container) {
            $count = $container->mockery_getExpectationCount();
            $this->addToAssertionCount($count);
        }

        \Mockery::close();
    }
}
