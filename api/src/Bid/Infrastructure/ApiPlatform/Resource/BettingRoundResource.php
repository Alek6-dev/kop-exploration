<?php

declare(strict_types=1);

namespace App\Bid\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Bid\Domain\Model\BettingRoundInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'BettingRound',
    operations: [
    ],
    security: "is_granted('IS_AUTHENTICATED_FULLY')",
)]
class BettingRoundResource
{
    /**
     * @param array<BettingRoundTeamResource>|null   $bettingRoundTeams
     * @param array<BettingRoundDriverResource>|null $bettingRoundDrivers
     */
    public function __construct(
        #[Assert\Uuid]
        #[ApiProperty(identifier: true)]
        public ?string $uuid = null,
        #[Assert\Positive]
        public ?int $round = null,
        public bool $isSetBySystem = false,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?array $bettingRoundTeams = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?array $bettingRoundDrivers = null,
    ) {
    }

    public static function fromModel(BettingRoundInterface $model): self
    {
        return new self(
            $model->getUuid(),
            $model->getRound(),
            $model->isSetBySystem(),
            $model->getBettingRoundTeam() ? [BettingRoundTeamResource::fromModel($model->getBettingRoundTeam())] : [],
            $model->getBettingRoundDrivers()->map(fn ($bettingRoundDriver) => BettingRoundDriverResource::fromModel($bettingRoundDriver))->toArray(),
        );
    }
}
