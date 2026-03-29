<?php

declare(strict_types=1);

namespace App\Bid\Application\Command\CompleteBidBySystem;

use App\Bid\Domain\Model\BettingRoundInterface;
use App\Bid\Infrastructure\Doctrine\Entity\BettingRoundDriver;
use App\Bid\Infrastructure\Doctrine\Entity\BettingRoundTeam;
use App\Driver\Domain\Model\DriverInterface;
use App\Shared\Application\Command\AsCommandHandler;
use App\Team\Domain\Model\TeamInterface;

#[AsCommandHandler]
final readonly class CompleteBidBySystemCommandHandler
{
    public function __invoke(CompleteBidBySystemCommand $command): BettingRoundInterface
    {
        if ($command->item instanceof TeamInterface) {
            $bettingRoundTeam = $command->bettingRound->getBettingRoundTeam() ?? new BettingRoundTeam();

            $command->bettingRound->setBettingRoundTeam($bettingRoundTeam
                ->setTeam($command->item)
                ->setBidAmount($command->item->getMinValue())
            );
        } elseif ($command->item instanceof DriverInterface) {
            $command->bettingRound->addBettingRoundDriver((new BettingRoundDriver())
                ->setDriver($command->item)
                ->setBidAmount($command->item->getMinValue())
            );
        }

        return $command->bettingRound;
    }
}
