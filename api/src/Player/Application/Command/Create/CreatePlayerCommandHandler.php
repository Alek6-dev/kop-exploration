<?php

declare(strict_types=1);

namespace App\Player\Application\Command\Create;

use App\Player\Domain\Exception\PlayerException;
use App\Player\Domain\Model\PlayerInterface;
use App\Player\Domain\Repository\PlayerRepositoryInterface;
use App\Player\Infrastructure\Doctrine\Entity\Player;
use App\Shared\Application\Command\AsCommandHandler;

#[AsCommandHandler]
final readonly class CreatePlayerCommandHandler
{
    public function __construct(
        private PlayerRepositoryInterface $repository,
    ) {
    }

    public function __invoke(CreatePlayerCommand $command): PlayerInterface
    {
        $playersAlreadyRegistered = $command->championship->getPlayers();

        $model = (new Player())
            ->setUser($command->user)
            ->setChampionship($command->championship)
            ->setRemainingBudget($command->championship->getInitialBudget())
            ->setRemainingUsageDriver1($command->championship->getInitialUsageDriver())
            ->setRemainingUsageDriver2($command->championship->getInitialUsageDriver())
            ->setRemainingDuelUsageDriver1($command->championship->getInitialUsageDriver())
            ->setRemainingDuelUsageDriver2($command->championship->getInitialUsageDriver())
            ->setName($command->playerName)
        ;

        if ($playersAlreadyRegistered) {
            if ($command->championship->getNumberOfPlayers()?->value < $playersAlreadyRegistered->count()) {
                throw PlayerException::maxPlayerReached($command->championship->getName());
            }

            $playersAlreadyRegistered->exists(function (int $key, PlayerInterface $playerRegistered) use ($model, $command): bool {
                if ($model->getUser()->getId() === $playerRegistered->getUser()->getId()) {
                    throw PlayerException::alreadyRegistered($command->user->getPseudo(), $command->championship->getName());
                }

                if ($model->getName() === $playerRegistered->getName()) {
                    throw PlayerException::nameAlreadyUsed($model->getName(), $command->championship->getName());
                }

                return true;
            });
        }

        $this->repository->add($model);

        return $model;
    }
}
