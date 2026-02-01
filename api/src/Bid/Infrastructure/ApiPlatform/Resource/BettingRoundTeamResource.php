<?php

declare(strict_types=1);

namespace App\Bid\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Bid\Domain\Model\BettingRoundTeamInterface;
use App\Team\Infrastructure\ApiPlatform\Resource\TeamResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'BettingRoundTeam',
    operations: [
    ],
    security: "is_granted('IS_AUTHENTICATED_FULLY')",
)]
class BettingRoundTeamResource
{
    public function __construct(
        #[Assert\Uuid]
        #[ApiProperty(identifier: true)]
        public ?string $uuid = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?TeamResource $team = null,
        #[Assert\Positive]
        public ?int $bidAmount = null,
    ) {
    }

    public static function fromModel(BettingRoundTeamInterface $model): self
    {
        return new self(
            $model->getUuid(),
            TeamResource::fromModel($model->getTeam()),
            $model->getBidAmount(),
        );
    }
}
