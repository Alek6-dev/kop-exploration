<?php

declare(strict_types=1);

namespace App\Championship\Infrastructure\Symfony\Command;

use App\Bid\Domain\Model\BettingRoundDriverInterface;
use App\Bid\Domain\Model\BettingRoundInterface;
use App\Bid\Domain\Repository\BettingRoundRepositoryInterface;
use App\Championship\Domain\Enum\ChampionshipStatusEnum;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Championship\Domain\Repository\ChampionshipRepositoryInterface;
use App\Player\Domain\Model\PlayerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:championship:assign-item',
    description: 'Assign drivers and team to the players which won the betting round',
)]
final class ChampionshipAssignToPlayerCommand extends Command
{
    public function __construct(
        private readonly ChampionshipRepositoryInterface $championshipRepository,
        private readonly BettingRoundRepositoryInterface $bettingRoundRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $championships = $this->championshipRepository
            ->withStatus(ChampionshipStatusEnum::BID_IN_PROGRESS)
            ->withBettingRoundOver(new \DateTimeImmutable())
        ;

        /** @var ChampionshipInterface $championship */
        foreach ($championships as $championship) {
            $bettingRounds = $this->bettingRoundRepository
                ->withRound($championship->getCurrentRound())
                ->withChampionship($championship)
                ->withIsSetBySystem(false)
                ->orderByCreatedAt('ASC')
                ->getResult();
            if (0 === \count($bettingRounds)) {
                // if some players with no full selection have no betting round or if every player have full selection
                goto persist;
            }
            $driversToAssign = [];
            $teamsToAssign = [];
            /** @var BettingRoundInterface $bettingRound */
            foreach ($bettingRounds as $bettingRound) {
                /** @var BettingRoundDriverInterface $bettingRoundDriver */
                foreach ($bettingRound->getBettingRoundDrivers() as $bettingRoundDriver) {
                    if (\array_key_exists($bettingRoundDriver->getDriver()->getId(), $driversToAssign)
                        && $bettingRoundDriver->getBidAmount() <= $driversToAssign[$bettingRoundDriver->getDriver()->getId()]['amount']
                    ) {
                        continue;
                    }
                    $driversToAssign[$bettingRoundDriver->getDriver()->getId()] = [
                        'player' => $bettingRound->getPlayer(),
                        'created_at' => $bettingRound->getCreatedAt(),
                        'amount' => $bettingRoundDriver->getBidAmount(),
                        'entity' => $bettingRoundDriver->getDriver(),
                    ];
                }
                if ($bettingRound->getBettingRoundTeam()) {
                    $bettingRoundTeam = $bettingRound->getBettingRoundTeam();
                    if (\array_key_exists($bettingRoundTeam->getTeam()->getId(), $teamsToAssign)
                        && $bettingRoundTeam->getBidAmount() <= $teamsToAssign[$bettingRoundTeam->getTeam()->getId()]['amount']
                    ) {
                        continue;
                    }
                    $teamsToAssign[$bettingRoundTeam->getTeam()->getId()] = [
                        'player' => $bettingRound->getPlayer(),
                        'created_at' => $bettingRound->getCreatedAt(),
                        'amount' => $bettingRoundTeam->getBidAmount(),
                        'entity' => $bettingRoundTeam->getTeam(),
                    ];
                }
            }

            $playersToUpdate = [];
            foreach (['driver' => $driversToAssign, 'team' => $teamsToAssign] as $type => $itemToAssign) {
                foreach ($itemToAssign as $item) {
                    /** @var PlayerInterface $player */
                    $player = $item['player'];
                    if (\array_key_exists($player->getId(), $playersToUpdate)) {
                        $player = $playersToUpdate[$player->getId()];
                    }
                    if ('driver' === $type) {
                        if ($player->getSelectedDriver1()) {
                            $player->setSelectedDriver2($item['entity']);
                        } else {
                            $player->setSelectedDriver1($item['entity']);
                        }
                    } else {
                        $player->setSelectedTeam($item['entity']);
                    }
                    $player->setRemainingBudget($player->getRemainingBudget() - $item['amount']);
                    $playersToUpdate[$player->getId()] = $player;
                }
            }

            foreach ($playersToUpdate as $player) {
                $this->entityManager->persist($player);
            }

            persist:
            $championship->setStatus(ChampionshipStatusEnum::BID_RESULT_PROCESSED);
            $this->entityManager->persist($championship);
            $this->entityManager->flush();
        }

        return Command::SUCCESS;
    }
}
