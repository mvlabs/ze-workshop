<?php

declare(strict_types=1);

namespace AppTest\Infrastructure\Repository;

use App\Domain\Value\ChocolateId;
use App\Infrastructure\Repository\ChocolateFilters;
use PHPUnit\Framework\TestCase;

final class ChocolateFiltersTest extends TestCase
{
    public function filtersDataProvider(): array
    {
        $id = (string) ChocolateId::new();

        return [
            [
                [],
                '',
                []
            ],
            [
                ['description' => 'very good'],
                'WHERE c.description = :description',
                ['description' => 'very good']
            ],
            [
                ['not-a-key' => 'not a value'],
                '',
                []
            ],
            [
                ['id' => $id, 'description' => 'very good', 'not-a-key' => 'not a value'],
                'WHERE c.id = :id AND c.description = :description',
                ['id' => $id, 'description' => 'very good']
            ]
        ];
    }

    /**
     * @dataProvider filtersDataProvider
     */
    public function testFilters(array $filters, string $query, array $data): void
    {
        $chocolateFilters = new ChocolateFilters($filters);

        self::assertSame($query, $chocolateFilters->where());
        self::assertSame($data, $chocolateFilters->data());
    }
}
