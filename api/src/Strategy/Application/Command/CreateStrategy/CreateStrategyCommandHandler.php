<?php

declare(strict_types=1);

namespace App\Strategy\Application\Command\CreateStrategy;

use App\Shared\Application\Command\AsCommandHandler;
use App\Strategy\Domain\Model\StrategyInterface;
use App\Strategy\Infrastructure\Doctrine\Entity\Strategy;

#[AsCommandHandler]
final readonly class CreateStrategyCommandHandler
{
    public function __invoke(CreateStrategyCommand $command): StrategyInterface
    {
        return (new Strategy())
            ->setChampionship($command->championship)
            ->setRace($command->race)
            ->setPlayer($command->player)
            ->setDriver($command->driver)
        ;
    }
}
