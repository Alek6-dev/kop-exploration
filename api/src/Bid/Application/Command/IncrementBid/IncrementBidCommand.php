<?php

declare(strict_types=1);

namespace App\Bid\Application\Command\IncrementBid;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Shared\Application\Command\CommandInterface;

/**
 * @implements CommandInterface<self>
 */
class IncrementBidCommand implements CommandInterface
{
    public function __construct(
        public ChampionshipInterface $championship,
    ) {
    }
}
