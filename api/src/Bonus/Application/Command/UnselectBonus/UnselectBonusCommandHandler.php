<?php

declare(strict_types=1);

namespace App\Bonus\Application\Command\UnselectBonus;

use App\Bonus\Domain\Exception\BonusException;
use App\Bonus\Domain\Model\BonusApplicationInterface;
use App\Bonus\Domain\Repository\BonusApplicationRepositoryInterface;
use App\Duel\Domain\Model\DuelInterface;
use App\Shared\Application\Command\AsCommandHandler;
use App\Strategy\Domain\Model\StrategyInterface;

#[AsCommandHandler]
final readonly class UnselectBonusCommandHandler
{
    public function __construct(
        private BonusApplicationRepositoryInterface $bonusApplicationRepository,
    ) {
    }

    public function __invoke(UnselectBonusCommand $command): void
    {
        if (!$command->entity->isActive()) {
            throw BonusException::impossibleToSelect();
        }
        $repository = $this->bonusApplicationRepository;
        switch (true) {
            case $command->entity instanceof StrategyInterface:
                $repository = $repository->withStrategy($command->entity);
                break;
            case $command->entity instanceof DuelInterface:
                $repository = $this->bonusApplicationRepository->withDuel($command->entity);
                break;
        }

        $repository = $repository->withPlayer($command->player);

        /** @var ?BonusApplicationInterface $bonusApplication */
        $bonusApplication = $repository->first();

        if ($bonusApplication) {
            $repository->remove($bonusApplication);
        }
    }
}
