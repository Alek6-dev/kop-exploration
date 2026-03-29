<?php

declare(strict_types=1);

namespace App\CreditWallet\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\CreditWallet\Infrastructure\ApiPlatform\OpenApi\StripeCheckoutFilter;
use App\CreditWallet\Infrastructure\ApiPlatform\State\Provider\StripeLinkProvider;

#[ApiResource(
    shortName: 'StripeLink',
    operations: [
        new Get(
            uriTemplate: '/payment/checkout',
            paginationEnabled: false,
            filters: [StripeCheckoutFilter::class],
            provider: StripeLinkProvider::class,
        ),
    ],
    security: "is_granted('IS_AUTHENTICATED_FULLY')",
)]
class StripeLinkResource
{
    public function __construct(
        public string $urlCallback,
    ) {
    }
}
