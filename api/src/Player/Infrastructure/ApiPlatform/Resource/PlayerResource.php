<?php

declare(strict_types=1);

namespace App\Player\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Bid\Application\Dto\AddBidDto;
use App\Bid\Domain\Model\BettingRoundDriverInterface;
use App\Bid\Domain\Model\BettingRoundInterface;
use App\Bid\Infrastructure\ApiPlatform\Resource\BettingRoundFlatResource;
use App\Bid\Infrastructure\ApiPlatform\Resource\BettingRoundResource;
use App\Bid\Infrastructure\ApiPlatform\State\Processor\CreateBidProcessor;
use App\Bonus\Infrastructure\ApiPlatform\Resource\BonusApplicationResource;
use App\Championship\Domain\Enum\ChampionshipStatusEnum;
use App\Driver\Infrastructure\ApiPlatform\Resource\DriverResource;
use App\Duel\Infrastructure\ApiPlatform\Resource\PlayerDuelResource;
use App\Player\Domain\Model\PlayerInterface;
use App\Player\Infrastructure\ApiPlatform\State\Provider\PlayerItemByChampionshipProvider;
use App\Strategy\Infrastructure\ApiPlatform\Resource\PlayerStrategyResource;
use App\Team\Infrastructure\ApiPlatform\Resource\TeamResource;
use App\User\Infrastructure\ApiPlatform\Resource\UserResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'Player',
    operations: [
        new Get(),
        new Get(
            uriTemplate: '/championships/{championshipUuid}/my-player',
            uriVariables: [
                'championshipUuid' => 'string',
            ],
            paginationEnabled: false,
            provider: PlayerItemByChampionshipProvider::class,
        ),
        new Post(
            uriTemplate: '/championships/{championshipUuid}/bid',
            uriVariables: [
                'championshipUuid' => 'string',
            ],
            paginationEnabled: false,
            input: AddBidDto::class,
            processor: CreateBidProcessor::class,
        ),
    ],
    normalizationContext: [
        'skip_null_values' => false,
    ],
    security: "is_granted('IS_AUTHENTICATED_FULLY')"
)]
class PlayerResource
{
    /**
     * @param array<BettingRoundResource>|null $bettingRounds
     */
    public function __construct(
        #[Assert\Uuid]
        #[ApiProperty(identifier: true)]
        public ?string $uuid = null,
        #[Assert\Length(min: 3, max: 25)]
        public ?string $name = null,
        #[Assert\PositiveOrZero]
        public ?int $remainingBudget = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?TeamResource $selectedTeam = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?DriverResource $selectedDriver1 = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?DriverResource $selectedDriver2 = null,
        #[Assert\PositiveOrZero]
        public ?int $remainingUsageDriver1 = null,
        #[Assert\PositiveOrZero]
        public ?int $remainingUsageDriver2 = null,
        #[Assert\PositiveOrZero]
        public ?int $remainingDuelUsageDriver1 = null,
        #[Assert\PositiveOrZero]
        public ?int $remainingDuelUsageDriver2 = null,
        #[Assert\PositiveOrZero]
        public ?int $maxRemainingUsageDriver = null,
        #[Assert\NotNull]
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?UserResource $user = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?array $bettingRounds = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?BettingRoundResource $currentBettingRound = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?BettingRoundFlatResource $bettingRoundDriver1Won = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?BettingRoundFlatResource $bettingRoundDriver2Won = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?BettingRoundFlatResource $bettingRoundTeamWon = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?PlayerStrategyResource $currentStrategy = null,
        #[ApiProperty(readableLink: true, writableLink: false)]
        public ?PlayerDuelResource $currentDuel = null,
        public ?int $point = 0,
        public ?int $position = null,
        public ?int $score = 0,
    ) {
    }

    public static function fromModel(PlayerInterface $model): self
    {
        $bettingRoundDriver1Won = null;
        $bettingRoundDriver2Won = null;
        $bettingRoundTeamWon = null;
        $strategy = null;
        $duel = null;
        /** @var BettingRoundInterface $bettingRound */
        foreach ($model->getBettingRounds() as $bettingRound) {
            if ($model->getSelectedTeam() && $bettingRound->getBettingRoundTeam() && $bettingRound->getBettingRoundTeam()->getTeam() === $model->getSelectedTeam()) {
                $bettingRoundTeamWon = new BettingRoundFlatResource(
                    name: $model->getSelectedTeam()->getName(),
                    uuidItem: $model->getSelectedTeam()->getUuid(),
                    image: $model->getSelectedTeam()->getRelativeImagePath(),
                    color: $model->getSelectedTeam()->getColor(),
                    amount: $bettingRound->getBettingRoundTeam()->getBidAmount(),
                    round: $bettingRound->getRound(),
                    assignBySystem: $bettingRound->isSetBySystem(),
                );
            }
            $bettingRoundDrivers = $bettingRound->getBettingRoundDrivers();
            if (($model->getSelectedDriver1() || $model->getSelectedDriver2()) && $bettingRoundDrivers) {
                /** @var BettingRoundDriverInterface $bettingRoundDriver */
                foreach ($bettingRoundDrivers as $bettingRoundDriver) {
                    if ($bettingRoundDriver->getDriver() === $model->getSelectedDriver1() || $bettingRoundDriver->getDriver() === $model->getSelectedDriver2()) {
                        if (!$bettingRoundDriver1Won) {
                            $bettingRoundDriver1Won = new BettingRoundFlatResource(
                                name: $bettingRoundDriver->getDriver()->getName(),
                                uuidItem: $bettingRoundDriver->getDriver()->getUuid(),
                                image: $bettingRoundDriver->getDriver()->getRelativeImagePath(),
                                color: $bettingRoundDriver->getDriver()->getColor(),
                                amount: $bettingRoundDriver->getBidAmount(),
                                round: $bettingRound->getRound(),
                                assignBySystem: $bettingRound->isSetBySystem(),
                            );
                        } else {
                            $bettingRoundDriver2Won = new BettingRoundFlatResource(
                                name: $bettingRoundDriver->getDriver()->getName(),
                                uuidItem: $bettingRoundDriver->getDriver()->getUuid(),
                                image: $bettingRoundDriver->getDriver()->getRelativeImagePath(),
                                color: $bettingRoundDriver->getDriver()->getColor(),
                                amount: $bettingRoundDriver->getBidAmount(),
                                round: $bettingRound->getRound(),
                                assignBySystem: $bettingRound->isSetBySystem(),
                            );
                        }
                    }
                }
            }
        }
        if (ChampionshipStatusEnum::ACTIVE === $model->getChampionship()->getStatus()) {
            $strategy = $model->getCurrentStrategy() ? PlayerStrategyResource::fromModel($model->getCurrentStrategy()) : null;
            if ($model->getCurrentDuel()) {
                $opponent = $model->getCurrentDuel()->getPlayer1();
                $driver = $model->getCurrentDuel()->getPlayerDriver2();
                $bonusApplication = $model->getCurrentDuel()?->getBonusApplicationByPlayer2OnRace($model->getCurrentDuel()->getRace());
                if ($model->getCurrentDuel()->getPlayer1() === $model) {
                    $opponent = $model->getCurrentDuel()->getPlayer2();
                    $driver = $model->getCurrentDuel()->getPlayerDriver1();
                    $bonusApplication = $model->getCurrentDuel()?->getBonusApplicationByPlayer1OnRace($model->getCurrentDuel()->getRace());
                }
                $duel = new PlayerDuelResource(
                    $model->getCurrentDuel()->getUuid(),
                    $driver ? DriverResource::fromModel($driver) : null,
                    PlayerFlatResource::fromModel($opponent),
                    $bonusApplication ? BonusApplicationResource::fromModel($bonusApplication) : null,
                );
            }
        }

        return new self(
            $model->getUuid(),
            $model->getName(),
            $model->getRemainingBudget(),
            $model->getSelectedTeam() ? TeamResource::fromModel($model->getSelectedTeam()) : null,
            $model->getSelectedDriver1() ? DriverResource::fromModel($model->getActiveSelectedDriver1()) : null,
            $model->getSelectedDriver2() ? DriverResource::fromModel($model->getActiveSelectedDriver2()) : null,
            $model->getRemainingUsageDriver1(),
            $model->getRemainingUsageDriver2(),
            $model->getRemainingDuelUsageDriver1(),
            $model->getRemainingDuelUsageDriver2(),
            $model->getChampionship()->getInitialUsageDriver(),
            UserResource::fromModel($model->getUser()),
            $model->getBettingRounds() ? $model->getBettingRounds()->map(fn (BettingRoundInterface $bettingRound) => BettingRoundResource::fromModel($bettingRound))->toArray() : null,
            $model->getBettingRound($model->getChampionship()->getCurrentRound()) ? BettingRoundResource::fromModel($model->getBettingRound($model->getChampionship()->getCurrentRound())) : null,
            $bettingRoundDriver1Won,
            $bettingRoundDriver2Won,
            $bettingRoundTeamWon,
            $strategy,
            $duel,
            $model->getPoints(),
            $model->getPosition(),
            $model->getScore(),
        );
    }
}
