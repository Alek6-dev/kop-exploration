<?php

declare(strict_types=1);

namespace App\Admin\Application\Query\Stat\CountUsers;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Shared\Application\Query\QueryInterface;
use App\Shared\Domain\Enum\User\StatusEnum;

/**
 * @implements QueryInterface<ChampionshipInterface>
 */
final readonly class CountUsersQuery implements QueryInterface
{
    /**
     * @param array<StatusEnum> $statuses
     */
    public function __construct(
        public array $statuses,
    ) {
    }
}
