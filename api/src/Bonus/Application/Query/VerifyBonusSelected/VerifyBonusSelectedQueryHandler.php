<?php

declare(strict_types=1);

namespace App\Bonus\Application\Query\VerifyBonusSelected;

use App\Bonus\Domain\Exception\BonusException;
use App\Bonus\Domain\Model\BonusApplicationInterface;
use App\Bonus\Domain\Repository\BonusApplicationRepositoryInterface;
use App\Duel\Domain\Exception\DuelException;
use App\Duel\Domain\Model\DuelInterface;
use App\Shared\Application\Query\AsQueryHandler;
use App\Strategy\Domain\Exception\StrategyException;
use App\Strategy\Domain\Model\StrategyInterface;

#[AsQueryHandler]
final readonly class VerifyBonusSelectedQueryHandler
{
    public function __construct(private BonusApplicationRepositoryInterface $repository)
    {
    }

    public function __invoke(VerifyBonusSelectedQuery $query): void
    {
        $bonusApplied = $this->repository
            ->withPlayer($query->player)
        ;
        $entity = $query->entity;
        switch (true) {
            case $entity instanceof StrategyInterface:
                if (!$entity->isActive()) {
                    throw StrategyException::raceIsNotActive($entity->getUuid());
                }
                $bonusApplied = $bonusApplied->withStrategy($entity);
                break;
            case $entity instanceof DuelInterface:
                if (!$entity->isActive()) {
                    throw DuelException::raceIsNotActive($entity->getUuid(), $entity->getRace()->getUuid());
                }
                $bonusApplied = $bonusApplied->withDuel($entity);
                break;
        }

        $bonusApplied = $bonusApplied->first();
        /** @var ?BonusApplicationInterface $bonusApplied */
        if ($bonusApplied) {
            BonusException::bonusAlreadySelected($query->player->getUuid());
        }
    }
}
