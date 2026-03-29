<?php

declare(strict_types=1);

namespace App\Championship\Application\Command\CreateAllChampionshipRaces;

use App\Championship\Domain\Repository\ChampionshipRepositoryInterface;
use App\Championship\Infrastructure\Doctrine\Entity\ChampionshipRace;
use App\Season\Domain\Repository\SeasonRaceRepositoryInterface;
use App\Shared\Application\Command\AsCommandHandler;

#[AsCommandHandler]
final readonly class CreateAllChampionshipRacesCommandHandler
{
    public function __construct(
        private ChampionshipRepositoryInterface $repository,
        private SeasonRaceRepositoryInterface $seasonRaceRepository,
    ) {
    }

    public function __invoke(CreateAllChampionshipRacesCommand $command): void
    {
        $championship = $command->championship;
        $races = $this->seasonRaceRepository;

        foreach ($races as $race) {
            $championshipRace = new ChampionshipRace();
            $championshipRace
                ->setChampionship($championship)
                ->setRace($race)
            ;
            $this->repository->add($championshipRace);
        }
    }
}
