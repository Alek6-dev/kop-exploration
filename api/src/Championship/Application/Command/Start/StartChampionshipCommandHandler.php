<?php

declare(strict_types=1);

namespace App\Championship\Application\Command\Start;

use App\Bid\Application\Command\IncrementBid\IncrementBidCommand;
use App\Championship\Domain\Enum\ChampionshipNumberPlayerEnum;
use App\Championship\Domain\Enum\ChampionshipStatusEnum;
use App\Championship\Domain\Exception\ChampionshipException;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Shared\Application\Command\AsCommandHandler;
use App\Shared\Application\Command\CommandBusInterface;

#[AsCommandHandler]
final readonly class StartChampionshipCommandHandler
{
    public function __construct(
        private CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(StartChampionshipCommand $command): ChampionshipInterface
    {
        $user = $command->user;
        $championship = $command->championship;

        if ($user !== $championship->getCreatedBy()) {
            throw ChampionshipException::wrongCreatorToStart();
        }

        if (ChampionshipNumberPlayerEnum::FOUR_PLAYERS->value > $championship->getPlayers()->count()) {
            throw ChampionshipException::minPlayersNotReached(ChampionshipNumberPlayerEnum::FOUR_PLAYERS->value);
        }

        if (0 !== $championship->getPlayers()->count() % 2) {
            throw ChampionshipException::playerCountNotEven();
        }

        if (ChampionshipStatusEnum::CREATED !== $championship->getStatus()) {
            throw ChampionshipException::cantBeStarted($championship->getUuid());
        }

        $championship->setStatus(ChampionshipStatusEnum::BID_IN_PROGRESS)
            ->setNumberOfPlayers(ChampionshipNumberPlayerEnum::from($championship->getPlayers()->count()))
            // Set invitation code with suffix to avoid collision when too many invitation codes exist
            ->setInvitationCode($command->championship->getInvitationCode().'_old_'.$command->championship->getId());

        return $this->commandBus->dispatch(new IncrementBidCommand($championship));
    }
}
