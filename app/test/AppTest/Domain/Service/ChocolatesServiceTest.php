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

    protected function assertPostConditions(): void
    {
        $container = Mockery::getContainer();
        if ($container != null) {
            $count = $container->mockery_getExpectationCount();
            $this->addToAssertionCount($count);
        }

        \Mockery::close();
    }
}
