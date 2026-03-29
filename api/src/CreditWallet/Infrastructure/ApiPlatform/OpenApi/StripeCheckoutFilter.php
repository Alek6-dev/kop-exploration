<?php

declare(strict_types=1);

namespace App\CreditWallet\Infrastructure\ApiPlatform\OpenApi;

use ApiPlatform\Api\FilterInterface;
use Symfony\Component\PropertyInfo\Type;

final readonly class StripeCheckoutFilter implements FilterInterface
{
    /**
     * @return array<string,array<string,string|bool>>
     */
    public function getDescription(string $resourceClass): array
    {
        return [
            'credit_pack_id' => [
                'type' => Type::BUILTIN_TYPE_STRING,
                'required' => true,
            ],
            'url_callback' => [
                'type' => Type::BUILTIN_TYPE_STRING,
                'required' => true,
            ],
        ];
    }
}
