<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

final class ChocolateFilters
{
    /**
     * @var array
     */
    private $chocolateFilters;

    public function __construct(array $filters)
    {
        $possibleFilters = [
            'id',
            'producer_id',
            'description',
            'cacao_percentage',
            'wrapper_type',
            'quantity'
        ];

        $this->chocolateFilters = array_filter(
            $filters,
            function (string $key) use ($possibleFilters) {
                return in_array($key, $possibleFilters);
            },
            ARRAY_FILTER_USE_KEY
        );
    }

    public function where(): string
    {
        if (empty($this->chocolateFilters)) {
            return '';
        }

        return 'WHERE ' .
            implode(' AND ', array_map(function (string $key) {
                return 'c.' . $key . ' = :' .$key;
            }, array_keys($this->chocolateFilters)));
    }

    public function data(): array
    {
        return $this->chocolateFilters;
    }
}
