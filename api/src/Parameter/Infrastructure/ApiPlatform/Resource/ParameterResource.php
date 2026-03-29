<?php

declare(strict_types=1);

namespace App\Parameter\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Parameter\Domain\Model\ParameterInterface;
use App\Parameter\Infrastructure\ApiPlatform\State\Provider\ParameterCollectionProvider;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'Parameter',
    operations: [
        new GetCollection(
            paginationEnabled: false,
            provider: ParameterCollectionProvider::class,
        ),
    ],
    security: "is_granted('PUBLIC_ACCESS')",
)]
class ParameterResource
{
    public function __construct(
        #[Assert\NotBlank]
        public ?string $code = null,
        #[Assert\NotBlank]
        public ?string $value = null,
    ) {
    }

    public static function fromModel(ParameterInterface $model): self
    {
        return new self(
            $model->getCode(),
            $model->getValue(),
        );
    }
}
