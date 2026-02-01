<?php

declare(strict_types=1);

namespace App\Season\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Season\Domain\Model\SeasonInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [],
)]
class SeasonResource
{
    public function __construct(
        #[Assert\Uuid]
        #[ApiProperty(identifier: true)]
        public ?string $uuid = null,
        #[Assert\NotBlank]
        public ?string $name = null,
        #[Assert\NotNull]
        public ?bool $isActive = null,
    ) {
    }

    public static function fromModel(SeasonInterface $model): self
    {
        return new self(
            $model->getUuid(),
            $model->getName(),
            $model->isActive(),
        );
    }
}
