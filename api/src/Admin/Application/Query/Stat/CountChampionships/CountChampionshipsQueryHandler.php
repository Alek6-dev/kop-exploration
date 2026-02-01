<?php

declare(strict_types=1);

namespace App\Admin\Application\Query\Stat\CountChampionships;

use App\Championship\Domain\Repository\ChampionshipRepositoryInterface;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class CountChampionshipsQueryHandler
{
    public function __construct(
        private ChampionshipRepositoryInterface $championshipRepository,
    ) {
    }

    public function __invoke(CountChampionshipsQuery $query): int
    {
        return $this->championshipRepository->withStatuses($query->statuses)
            ->count()
        ;
    }
}
