<?php

declare(strict_types=1);

namespace App\Championship\Application\Command\Start;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Shared\Application\Command\CommandInterface;
use App\User\Domain\Model\UserVisitorInterface;

/**
 * @implements CommandInterface<self>
 */
class StartChampionshipCommand implements CommandInterface
{
    public function __construct(
        public ChampionshipInterface $championship,
        public UserVisitorInterface $user,
    ) {
    }
}
