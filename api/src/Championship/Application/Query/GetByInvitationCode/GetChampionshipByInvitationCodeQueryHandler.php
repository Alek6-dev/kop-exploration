<?php

declare(strict_types=1);

namespace App\Championship\Application\Query\GetByInvitationCode;

use App\Championship\Domain\Exception\ChampionshipException;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Championship\Domain\Repository\ChampionshipRepositoryInterface;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class GetChampionshipByInvitationCodeQueryHandler
{
    public function __construct(private ChampionshipRepositoryInterface $repository)
    {
    }

    public function __invoke(GetChampionshipByInvitationCodeQuery $query): ChampionshipInterface
    {
        /** @var ?ChampionshipInterface $model */
        $model = $this->repository->getByInvitationCode($query->invitationCode);

        if (!$model) {
            throw ChampionshipException::invitationCodeNotFound($query->invitationCode);
        }

        return $model;
    }
}
