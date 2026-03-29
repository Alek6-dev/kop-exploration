<?php

declare(strict_types=1);

namespace App\Championship\Application\Command\Create;

use App\Championship\Domain\Enum\ChampionshipNumberRaceEnum;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Championship\Infrastructure\Doctrine\Entity\Championship;
use App\Parameter\Domain\Repository\ParameterRepositoryInterface;
use App\Shared\Application\Command\AsCommandHandler;

#[AsCommandHandler]
final readonly class CreateChampionshipCommandHandler
{
    public function __construct(
        private ParameterRepositoryInterface $parameterRepository,
    ) {
    }

    public function __invoke(CreateChampionshipCommand $command): ChampionshipInterface
    {
        $initialBudgetParam = $this->parameterRepository->getParameterByCode('player_initial_budget');

        $initialUsageDriver = 0;
        switch ($command->championshipNumberRace) {
            case ChampionshipNumberRaceEnum::FOUR_RACES:
                $initialUsageDriver = 3;
                break;
            case ChampionshipNumberRaceEnum::FIVE_RACES:
            case ChampionshipNumberRaceEnum::SIX_RACES:
                $initialUsageDriver = 4;
                break;
            case ChampionshipNumberRaceEnum::SEVEN_RACES:
            case ChampionshipNumberRaceEnum::HEIGHT_RACES:
                $initialUsageDriver = 5;
                break;
            case ChampionshipNumberRaceEnum::NINE_RACES:
            case ChampionshipNumberRaceEnum::TEN_RACES:
                $initialUsageDriver = 6;
                break;
        }

        return (new Championship())
            ->setSeason($command->season)
            ->setCreatedBy($command->createdBy)
            ->setName($command->name)
            ->setJokerEnabled($command->jokerEnabled)
            ->setNumberOfRaces($command->championshipNumberRace)
            ->setNumberOfPlayers($command->championshipNumberPlayer)
            ->setInvitationCode($command->invitationCode)
            ->setInitialBudget((int) $initialBudgetParam->getValue())
            ->setInitialUsageDriver($initialUsageDriver)
        ;
    }
}
