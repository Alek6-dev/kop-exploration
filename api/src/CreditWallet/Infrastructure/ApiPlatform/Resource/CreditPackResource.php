<?php

declare(strict_types=1);

namespace App\CreditWallet\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\CreditWallet\Domain\Model\CreditPackInterface;
use App\CreditWallet\Infrastructure\ApiPlatform\State\Provider\CreditPackCollectionProvider;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'CreditPack',
    operations: [
        new GetCollection(
            provider: CreditPackCollectionProvider::class,
        ),
    ],
    normalizationContext: [
        'skip_null_values' => false,
    ],
    security: "is_granted('IS_AUTHENTICATED_FULLY')",
)]
class CreditPackResource
{
    public function __construct(
        #[Assert\Uuid]
        #[ApiProperty(identifier: true)]
        public ?string $uuid = null,
        public ?int $credit = null,
        public ?float $price = null,
        public ?string $message = null,
    ) {
    }

    public static function fromModel(CreditPackInterface $model): self
    {
        return new self(
            $model->getUuid(),
            $model->getCredit(),
            $model->getPrice(),
            $model->getMessage(),
        );
    }
}
