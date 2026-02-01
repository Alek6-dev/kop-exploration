<?php

declare(strict_types=1);

namespace App\CreditWallet\Application\Dto;

use App\CreditWallet\Infrastructure\Stripe\Client\StripeClient;
use Stripe\Price;
use Stripe\Product;

readonly class CreditPackFromStripeDto
{
    public function __construct(
        public string $name,
        public int $quantity,
        public float $price,
        public string $productId,
        public string $priceId,
    ) {
    }

    public static function fromStripeData(Product $product, Price $price): self
    {
        return new self(
            $product->name,
            (int) $product->metadata->offsetGet(StripeClient::METADATA_CREDITS_QUANTITY_KEY),
            $price->unit_amount / 100,
            $product->id,
            $price->id,
        );
    }
}
