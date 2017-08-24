<?php

declare(strict_types=1);

namespace App\Infrastructure\Hydrators;

use App\Domain\Entity\Chocolate;
use Zend\Hydrator\HydratorInterface;

final class SerializeExtractor implements HydratorInterface
{
    public function extract($object)
    {
        /** @var Chocolate $object */
        return $object->jsonSerialize();
    }

    public function hydrate(array $data, $object)
    {
    }
}
