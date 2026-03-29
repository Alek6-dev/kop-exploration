<?php

declare(strict_types=1);

namespace App\Championship\Application\Command\Active;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Shared\Application\Command\CommandInterface;

class ActiveChampionshipCommand implements CommandInterface
{
    public function __construct(
        public ChampionshipInterface $championship,
    ) {
    }
}
