<?php

declare(strict_types=1);

namespace App\Bonus\Application\Query\GetBonusToApplyCollection;

use App\Bonus\Domain\Model\BonusApplicationInterface;
use App\Bonus\Domain\Repository\BonusApplicationRepositoryInterface;
use App\Duel\Domain\Model\DuelInterface;
use App\Shared\Application\Query\AsQueryHandler;
use App\Strategy\Domain\Model\StrategyInterface;

#[AsQueryHandler]
final readonly class GetBonusToApplyCollectionQueryHandler
{
    public function __construct(private BonusApplicationRepositoryInterface $repository)
    {
    }

    /**
     * @return BonusApplicationInterface[]
     */
    public function __invoke(GetBonusToApplyCollectionQuery $query): array
    {
        $repository = $this->repository
            ->withChampionship($query->entity->getChampionship())
            ->withTarget($query->player)
            ->withRace($query->race)
            ->withOrderBySort()
            ->withOrderByCreatedAt('ASC')
        ;
        match (true) {
            $query->entity instanceof StrategyInterface => $repository = $repository->withDuelIsNull(),
            $query->entity instanceof DuelInterface => $repository = $repository->withStrategyIsNull(),
        };

        return array_filter(
            $repository->getResult(),
            function (BonusApplicationInterface $bonusApplication) use (&$limitBonusesApplication) {
                $bonus = $bonusApplication->getBonus();
                $limitBonusesApplication[$bonus->getId()][] = $bonusApplication;
                if (isset($limitBonusesApplication[$bonus->getId()]) && $bonus->getCumulativeTimes() && $bonus->getCumulativeTimes() < \count($limitBonusesApplication[$bonus->getId()])) {
                    return false;
                }

                return true;
            }
        );
    }
}
