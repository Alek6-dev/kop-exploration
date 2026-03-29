<?php

declare(strict_types=1);

namespace App\Bid\Application\Command\IncrementBid;

use App\Bid\Domain\Exception\BidException;
use App\Championship\Domain\Enum\ChampionshipStatusEnum;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Shared\Application\Command\AsCommandHandler;

#[AsCommandHandler]
final readonly class IncrementBidCommandHandler
{
    public const string CURRENT_ROUND_END_PERIOD = '24';

    public function __invoke(IncrementBidCommand $command): ChampionshipInterface
    {
        $championship = $command->championship;
        if (!\in_array($championship->getStatus(), [ChampionshipStatusEnum::BID_IN_PROGRESS, ChampionshipStatusEnum::BID_RESULT_PROCESSED])) {
            throw BidException::newBettingRoundNotPossible($championship->getUuid());
        }

        $currentRound = (int) $championship->getCurrentRound();

        $championship
            ->setStatus(ChampionshipStatusEnum::BID_IN_PROGRESS)
            ->setCurrentRound(++$currentRound)
            ->setCurrentRoundEndDate((new \DateTimeImmutable())->add(new \DateInterval(sprintf('PT%sH', self::CURRENT_ROUND_END_PERIOD))))
        ;

        return $championship;
    }
}
