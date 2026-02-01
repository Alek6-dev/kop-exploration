<?php

declare(strict_types=1);

namespace App\User\Application\Query\Stat\CountDuels;

use App\Championship\Domain\Enum\ChampionshipRaceStatusEnum;
use App\Duel\Domain\Repository\DuelRepositoryInterface;
use App\Shared\Application\Query\AsQueryHandler;
use App\User\Domain\Exception\UserVisitorException;
use App\User\Domain\Model\UserVisitorInterface;
use App\User\Domain\Repository\UserVisitorRepositoryInterface;

#[AsQueryHandler]
final readonly class CountDuelsQueryHandler
{
    public function __construct(
        private UserVisitorRepositoryInterface $userVisitorRepository,
        private DuelRepositoryInterface $repository
    ) {
    }

    public function __invoke(CountDuelsQuery $query): int
    {
        /** @var ?UserVisitorInterface $user */
        $user = $this->userVisitorRepository->getByUuid($query->userUuid);

        if (!$user) {
            UserVisitorException::notFound($query->userUuid);
        }

        return $this->repository
            ->withStatus(ChampionshipRaceStatusEnum::OVER)
            ->withUserWin($user, $query->win)
            ->count()
        ;
    }
}
