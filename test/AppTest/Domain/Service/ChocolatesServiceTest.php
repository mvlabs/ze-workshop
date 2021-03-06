<?php

declare(strict_types=1);

namespace AppTest\Domain\Service;

use App\Domain\Entity\Chocolate;
use App\Domain\Entity\User;
use App\Domain\Service\ChocolatesService;
use App\Domain\Service\Exception\ChocolateNotFoundException;
use App\Domain\Value\Address;
use App\Domain\Value\ChocolateId;
use App\Domain\Value\Country;
use App\Domain\Value\Percentage;
use App\Domain\Value\Producer;
use App\Domain\Value\Quantity;
use App\Domain\Value\Status;
use App\Domain\Value\WrapperType;
use App\Infrastructure\Repository\Chocolates;
use Mockery;
use PHPUnit\Framework\TestCase;

final class ChocolatesServiceTest extends TestCase
{
    private $chocolates;

    /**
     * @var ChocolatesService
     */
    private $service;

    public function setUp(): void
    {
        $this->chocolates = Mockery::mock(Chocolates::class);
        $this->service = new ChocolatesService($this->chocolates);
    }

    public function testAll(): void
    {
        $this->chocolates->shouldReceive('all');

        $this->service->getAll();
    }

    public function testGetExistingChocolate(): void
    {
        $chocolateId = ChocolateId::new();
        $chocolate = Chocolate::submit(
            $chocolateId,
            Producer::fromNameAndAddress(
                'producer',
                Address::fromStreetNumberZipCodeCityRegionCountry(
                    'via Diqua',
                    '1A',
                    'AB123',
                    'Treviso',
                    'TV',
                    Country::fromStringCode('IT')
                )),
            'description',
            Percentage::integer(77),
            WrapperType::get(WrapperType::BOX),
            Quantity::grams(100),
            User::new('gigi', 'Zucon')
        );

        $this->chocolates->shouldReceive('findById')->with($chocolateId)->andReturn($chocolate);

        self::assertSame($chocolate, $this->service->getChocolate($chocolateId));
    }

    public function testGetNonExistingChocolate()
    {
        $chocolateId = ChocolateId::new();

        $this->chocolates->shouldReceive('findById')->with($chocolateId)->andReturn(null);

        $this->expectException(ChocolateNotFoundException::class);
        $this->expectExceptionMessage(sprintf('No chocolate was found for id %s', (string) $chocolateId));

        $this->service->getChocolate($chocolateId);
    }

    public function testSubmit(): void
    {
        $chocolateId = ChocolateId::new();
        $producer = Producer::fromNameAndAddress(
            'producer',
            Address::fromStreetNumberZipCodeCityRegionCountry(
                'via Diqua',
                '1A',
                'AB123',
                'Treviso',
                'TV',
                Country::fromStringCode('IT')
            )
        );
        $description = 'description';
        $percentage = Percentage::integer(77);
        $wrapper = WrapperType::get(WrapperType::BOX);
        $quantity = Quantity::grams(100);
        $user = User::new('gigi', 'Zucon');

        $this->chocolates->shouldReceive('add')->with(Mockery::on(function ($chocolate) {
            return $chocolate instanceof Chocolate;
        }));

        $this->service->submit(
            $chocolateId,
            $producer,
            $description,
            $percentage,
            $wrapper,
            $quantity,
            $user
        );
    }

    public function testApprove(): void
    {
        $user = User::newAdministrator('gigi', 'Zucon');
        $chocolate = Chocolate::submit(
            ChocolateId::new(),
            Producer::fromNameAndAddress(
                'producer',
                Address::fromStreetNumberZipCodeCityRegionCountry(
                    'via Diqua',
                    '1A',
                    'AB123',
                    'Treviso',
                    'TV',
                    Country::fromStringCode('IT')
                )),
            'description',
            Percentage::integer(77),
            WrapperType::get(WrapperType::BOX),
            Quantity::grams(100),
            $user
        );

        $this->chocolates->shouldReceive('approve')->with(Mockery::on(function (Chocolate $chocolate) {
            return $chocolate->status() === Status::get(Status::APPROVED);
        }));

        $this->service->approve($chocolate, $user);
    }

    public function testDelete(): void
    {
        $user = User::newAdministrator('gigi', 'Zucon');
        $chocolate = Chocolate::submit(
            ChocolateId::new(),
            Producer::fromNameAndAddress(
                'producer',
                Address::fromStreetNumberZipCodeCityRegionCountry(
                    'via Diqua',
                    '1A',
                    'AB123',
                    'Treviso',
                    'TV',
                    Country::fromStringCode('IT')
                )),
            'description',
            Percentage::integer(77),
            WrapperType::get(WrapperType::BOX),
            Quantity::grams(100),
            $user
        );

        $this->chocolates->shouldReceive('delete')->with(Mockery::on(function (Chocolate $chocolate) {
            return $chocolate->status() === Status::get(Status::DELETED);
        }));

        $this->service->delete($chocolate, $user);
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
