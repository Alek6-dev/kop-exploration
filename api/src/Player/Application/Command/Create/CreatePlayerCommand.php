<?php

declare(strict_types=1);

namespace App\Player\Application\Command\Create;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Shared\Application\Command\CommandInterface;
use App\User\Domain\Model\UserVisitorInterface;

/**
 * @implements CommandInterface<self>
 */
class CreatePlayerCommand implements CommandInterface
{
    public function __construct(
        public UserVisitorInterface $user,
        public ChampionshipInterface $championship,
        public string $playerName,
    ) {
    }
}
