<?php

declare(strict_types=1);

namespace App\CreditWallet\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\OpenApi\Model\Operation;
use App\CreditWallet\Domain\Model\CreditPackInterface;
use App\CreditWallet\Infrastructure\ApiPlatform\State\Provider\CreditPackCollectionProvider;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'CreditPack',
    description: 'Packs de crédits disponibles à l\'achat',
    operations: [
        new GetCollection(
            provider: CreditPackCollectionProvider::class,
            openapi: new Operation(
                summary: 'Liste des packs de crédits',
                description: 'Récupère tous les packs de crédits disponibles pour recharger le portefeuille.',
                tags: ['Portefeuille'],
            ),
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
        #[ApiProperty(
            identifier: true,
            description: 'Identifiant unique du pack',
            example: 'a1b2c3d4-e5f6-7a8b-9c0d-1e2f3a4b5c6d',
        )]
        public ?string $uuid = null,
        #[ApiProperty(
            description: 'Nombre de crédits inclus dans le pack',
            example: 50000000,
        )]
        public ?int $credit = null,
        #[ApiProperty(
            description: 'Prix du pack en euros',
            example: 4.99,
        )]
        public ?float $price = null,
        #[ApiProperty(
            description: 'Message commercial du pack',
            example: 'Pack Starter - Idéal pour débuter',
        )]
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
