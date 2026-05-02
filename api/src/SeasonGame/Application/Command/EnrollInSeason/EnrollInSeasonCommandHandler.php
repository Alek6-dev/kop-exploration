<?php

declare(strict_types=1);

namespace App\SeasonGame\Application\Command\EnrollInSeason;

use App\Season\Domain\Exception\SeasonException;
use App\Season\Domain\Repository\SeasonRepositoryInterface;
use App\SeasonGame\Infrastructure\Doctrine\Entity\SeasonParticipation;
use App\SeasonGame\Infrastructure\Doctrine\Repository\DoctrineSeasonParticipationRepository;
use App\Shared\Application\Command\AsCommandHandler;

#[AsCommandHandler]
final readonly class EnrollInSeasonCommandHandler
{
    public function __construct(
        private SeasonRepositoryInterface $seasonRepository,
        private DoctrineSeasonParticipationRepository $participationRepository,
    ) {
    }

    public function __invoke(EnrollInSeasonCommand $command): SeasonParticipation
    {
        $season = $this->seasonRepository->getLastIsActive();

        if (!$season) {
            throw SeasonException::notActiveSeason();
        }

        $existing = $this->participationRepository->findByUserAndActiveSeason($command->user);
        if ($existing) {
            return $existing;
        }

        $participation = new SeasonParticipation();
        $participation->setUser($command->user);
        $participation->setSeason($season);

        return $participation;
    }
}
