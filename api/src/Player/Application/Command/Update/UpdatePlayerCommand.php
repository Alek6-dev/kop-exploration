<?php

declare(strict_types=1);

namespace App\Player\Application\Command\Update;

use App\Driver\Domain\Model\DriverInterface;
use App\Shared\Application\Command\CommandInterface;
use App\Team\Domain\Model\TeamInterface;

/**
 * @implements CommandInterface<self>
 */
class UpdatePlayerCommand implements CommandInterface
{
    public function __construct(
        public string $uuid,
        public ?TeamInterface $selectedTeam,
        public ?DriverInterface $selectDriver1,
        public ?DriverInterface $selectDriver2,
    ) {
    }
}
