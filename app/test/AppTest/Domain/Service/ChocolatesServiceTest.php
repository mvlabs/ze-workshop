<?php

declare(strict_types=1);

namespace AppTest\Domain\Service;

use App\Domain\Service\ChocolatesService;
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

    public function setUp()
    {
        $this->chocolates = Mockery::mock(Chocolates::class);
        $this->service = new ChocolatesService($this->chocolates);
    }

    public function testAll()
    {
        $this->chocolates->shouldReceive('all');

        $this->service->getAll();
    }

    protected function assertPostConditions()
    {
        $container = Mockery::getContainer();
        if ($container != null) {
            $count = $container->mockery_getExpectationCount();
            $this->addToAssertionCount($count);
        }

        \Mockery::close();
    }
}
