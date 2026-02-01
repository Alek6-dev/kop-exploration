<?php

declare(strict_types=1);

namespace App\Bonus\Infrastructure\ApiPlatform\OpenApi;

use ApiPlatform\Api\FilterInterface;
use Symfony\Component\PropertyInfo\Type;

final readonly class BonusFilter implements FilterInterface
{
    public const string TYPE = 'type';
    public const string JOKER = 'isJoker';

    /**
     * @return array<string, array<string, string|bool>>
     */
    public function getDescription(string $resourceClass): array
    {
        return [
            self::TYPE => [
                'property' => 'type',
                'type' => Type::BUILTIN_TYPE_STRING,
                'required' => true,
            ],
            self::JOKER => [
                'property' => 'isJoker',
                'type' => Type::BUILTIN_TYPE_BOOL,
                'required' => false,
            ],
        ];
    }
}
