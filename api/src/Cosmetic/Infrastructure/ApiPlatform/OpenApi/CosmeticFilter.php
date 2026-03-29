<?php

declare(strict_types=1);

namespace App\Cosmetic\Infrastructure\ApiPlatform\OpenApi;

use ApiPlatform\Api\FilterInterface;
use Symfony\Component\PropertyInfo\Type;

final readonly class CosmeticFilter implements FilterInterface
{
    /**
     * @return array<string, array<string, string|bool>>
     */
    public function getDescription(string $resourceClass): array
    {
        return [
            'type' => [
                'property' => 'type',
                'type' => Type::BUILTIN_TYPE_INT,
                'required' => false,
            ],
        ];
    }
}
