<?php

declare(strict_types=1);

namespace App\SeasonGame\Application\Command\ApplySeasonBonus;

use App\SeasonGame\Domain\Exception\SeasonGameException;
use App\SeasonGame\Infrastructure\Doctrine\Entity\SeasonBonusUsage;
use App\SeasonGame\Infrastructure\Doctrine\Repository\DoctrineSeasonGPStrategyRepository;
use App\Shared\Application\Command\AsCommandHandler;

#[AsCommandHandler]
final readonly class ApplySeasonBonusCommandHandler
{
    public function __construct(
        private DoctrineSeasonGPStrategyRepository $strategyRepository,
    ) {
    }

    public function __invoke(ApplySeasonBonusCommand $command): SeasonBonusUsage
    {
        $participation = $command->participation;
        $bonusType = $command->bonusType;
        $price = $bonusType->price();

        if ($participation->getWalletBalance() < $price) {
            throw SeasonGameException::insufficientBudget();
        }

        $strategy = $this->strategyRepository->findByParticipationAndRace($participation, $command->raceUuid);

        $bonusUsage = new SeasonBonusUsage();
        $bonusUsage->setParticipation($participation);
        $bonusUsage->setBonusType($bonusType);
        $bonusUsage->setPricePaid($price);
        $bonusUsage->markAsUsed();

        if ($strategy) {
            $bonusUsage->setGpStrategy($strategy);
        }

        $participation->debitWallet($price);

        return $bonusUsage;
    }
}
