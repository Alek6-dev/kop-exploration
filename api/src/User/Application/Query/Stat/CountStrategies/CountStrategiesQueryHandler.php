<?php

declare(strict_types=1);

namespace App\User\Application\Query\Stat\CountStrategies;

use App\Championship\Domain\Enum\ChampionshipRaceStatusEnum;
use App\Shared\Application\Query\AsQueryHandler;
use App\Strategy\Domain\Repository\StrategyRepositoryInterface;
use App\User\Domain\Exception\UserVisitorException;
use App\User\Domain\Model\UserVisitorInterface;
use App\User\Domain\Repository\UserVisitorRepositoryInterface;

#[AsQueryHandler]
final readonly class CountStrategiesQueryHandler
{
    public function __construct(
        private UserVisitorRepositoryInterface $userVisitorRepository,
        private StrategyRepositoryInterface $repository
    ) {
    }

    public function __invoke(CountStrategiesQuery $query): int
    {
        /** @var ?UserVisitorInterface $user */
        $user = $this->userVisitorRepository->getByUuid($query->userUuid);

        if (!$user) {
            UserVisitorException::notFound($query->userUuid);
        }

        $strategies = $this->repository
            ->withStatus(ChampionshipRaceStatusEnum::OVER)
            ->withUser($user)
        ;

        if ($query->position) {
            $strategies = $strategies->withPosition($query->position);
        }

        return $strategies->count();
    }
}
