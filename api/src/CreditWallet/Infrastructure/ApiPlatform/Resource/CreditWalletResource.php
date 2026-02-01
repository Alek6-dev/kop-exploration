<?php

declare(strict_types=1);

namespace App\CreditWallet\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\CreditWallet\Domain\Model\CreditWalletInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'CreditWallet',
    operations: [],
    normalizationContext: [
        'skip_null_values' => false,
    ],
    security: "is_granted('IS_AUTHENTICATED_FULLY')",
)]
class CreditWalletResource
{
    public function __construct(
        #[Assert\Uuid]
        #[ApiProperty(identifier: true)]
        public ?string $uuid = null,
        public ?int $credit = null,
    ) {
    }

    public static function fromModel(CreditWalletInterface $model): self
    {
        return new self(
            $model->getUuid(),
            $model->getCredit(),
        );
    }
}
