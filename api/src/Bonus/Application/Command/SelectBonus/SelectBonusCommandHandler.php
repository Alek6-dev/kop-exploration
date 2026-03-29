<?php

declare(strict_types=1);

namespace App\Bonus\Application\Command\SelectBonus;

use App\Bonus\Application\Query\VerifyBonusSelected\VerifyBonusSelectedQuery;
use App\Bonus\Domain\Enum\TargetTypeEnum;
use App\Bonus\Domain\Exception\BonusException;
use App\Bonus\Domain\Model\BonusApplicationInterface;
use App\Bonus\Infrastructure\Doctrine\Entity\BonusApplication;
use App\Duel\Domain\Model\DuelInterface;
use App\Player\Domain\Exception\PlayerException;
use App\Shared\Application\Command\AsCommandHandler;
use App\Shared\Application\Query\QueryBusInterface;
use App\Strategy\Domain\Model\StrategyInterface;

#[AsCommandHandler]
final readonly class SelectBonusCommandHandler
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {
    }

    public function __invoke(SelectBonusCommand $command): BonusApplicationInterface
    {
        $championship = $command->entity->getChampionship();
        $target = $command->target;
        if ($target && $target->getChampionship() !== $championship) {
            PlayerException::notFound($command->target->getUuid());
        }

        if (!$command->entity->isActive()) {
            throw BonusException::impossibleToSelect();
        }

        if (TargetTypeEnum::PLAYER === $command->bonus->getTargetType() && !$target) {
            throw BonusException::needATarget($command->bonus->getUuid());
        }

        if (TargetTypeEnum::SELF === $command->bonus->getTargetType()) {
            $target = $command->player;
        }

        $this->queryBus->ask(new VerifyBonusSelectedQuery(
            $command->player,
            $command->entity,
        ));

        $currentRace = $championship->getCurrentChampionshipRace()->getRace();
        $activeBonusOnRace = $command->player->getBonusUsagesOnRace($currentRace);
        $budget = $command->player->getRemainingBudget();
        $activeBonusOnRace->map(function (BonusApplicationInterface $bonusApplication) use (&$budget) {
            $budget -= $bonusApplication->getBonus()->getPrice();
        });

        if (0 > $budget - $command->bonus->getPrice()) {
            throw PlayerException::amountToSubtractIsTooHigh($command->player->getUuid(), $budget, $command->bonus->getPrice());
        }

        return (new BonusApplication())
            ->setPlayer($command->player)
            ->setBonus($command->bonus)
            ->setChampionship($championship)
            ->setRace($currentRace)
            ->setTarget($target)
            ->setDuel($command->entity instanceof DuelInterface ? $command->entity : null)
            ->setStrategy($command->entity instanceof StrategyInterface ? $command->entity : null)
        ;
    }
}
