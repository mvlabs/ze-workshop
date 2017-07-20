<?php

declare(strict_types=1);

namespace AppTest\Domain\Service;

use App\Domain\Entity\User;
use App\Domain\Service\UsersService;
use App\Domain\Value\UserId;
use App\Infrastructure\Repository\Users;
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

    public function testById(): void
    {
        $user = User::new('gigi', 'zucon');

        $this->users->shouldReceive('findById')->with($user->id())->andReturn($user);

        self::assertSame($user, $this->service->byId($user->id()));
    }

    public function testByUsername(): void
    {
        $user = User::new('gigi', 'zucon');

        $this->users->shouldReceive('findByUsername')->with($user->username())->andReturn($user);

        self::assertSame($user, $this->service->byUsername($user->username()));
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
