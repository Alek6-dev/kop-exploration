<?php

declare(strict_types=1);

namespace App\Strategy\Application\Command\SelectDriver;

use App\Shared\Application\Command\AsCommandHandler;
use App\Strategy\Domain\Exception\StrategyException;
use App\Strategy\Domain\Model\StrategyInterface;
use App\Strategy\Domain\Repository\StrategyRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

#[AsCommandHandler]
final readonly class SelectDriverCommandHandler
{
    public function __construct(
        public StrategyRepositoryInterface $strategyRepository,
        public EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(SelectDriverCommand $command): StrategyInterface
    {
        $strategy = $command->strategy;

        $player = $strategy->getPlayer();

        $driver = $command->driver;

        $driver1 = $player->getActiveSelectedDriver1();
        $driver2 = $player->getActiveSelectedDriver2();

        if ($driver !== $driver1 && $driver !== $driver2) {
            StrategyException::driverNotFound($driver->getUuid());
        }

        if (($driver === $driver1 && 0 >= $player->getRemainingUsageDriver1())
            || ($driver === $driver2 && 0 >= $player->getRemainingUsageDriver2())) {
            StrategyException::notEnoughUsageForDriver($player->getUuid(), $driver->getUuid());
        }

        return $strategy
            ->setDriver($command->driver)
        ;
    }
}
