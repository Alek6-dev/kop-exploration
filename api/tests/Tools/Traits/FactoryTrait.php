<?php

namespace App\Tests\Tools\Traits;

use Symfony\Component\PropertyAccess\PropertyAccessor;

trait FactoryTrait
{
    protected static function populate(object $object, array $data): object
    {
        $accessor = new PropertyAccessor();

        array_map(static function (string $property, mixed $value) use ($object, $accessor) {
            $accessor->setValue($object, $property, $value);
        }, array_keys($data), array_values($data));

        return $object;
    }
}
