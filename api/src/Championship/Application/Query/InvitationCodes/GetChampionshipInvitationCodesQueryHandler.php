<?php

declare(strict_types=1);

namespace App\Championship\Application\Query\InvitationCodes;

use App\Championship\Domain\Repository\ChampionshipRepositoryInterface;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class GetChampionshipInvitationCodesQueryHandler
{
    public function __construct(private ChampionshipRepositoryInterface $repository)
    {
    }

    /**
     * @return array<string>
     */
    public function __invoke(GetChampionshipInvitationCodesQuery $query): array
    {
        return $this->repository->getInvitationCodes();
    }
}
