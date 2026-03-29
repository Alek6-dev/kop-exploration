<?php

declare(strict_types=1);

namespace App\Championship\Infrastructure\ApiPlatform\OpenApi;

use ApiPlatform\Api\FilterInterface;
use Symfony\Component\PropertyInfo\Type;

final readonly class ChampionshipFilter implements FilterInterface
{
    /**
     * @return array<string, array<string, string|bool>>
     */
    public function getDescription(string $resourceClass): array
    {
        return [
            'isActive' => [
                'property' => 'status',
                'type' => Type::BUILTIN_TYPE_BOOL,
                'required' => false,
            ],
        ];
    }
}
