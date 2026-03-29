<?php

declare(strict_types=1);

namespace App\Championship\Application\Query\GetByInvitationCode;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Shared\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<ChampionshipInterface>
 */
final readonly class GetChampionshipByInvitationCodeQuery implements QueryInterface
{
    public function __construct(
        public string $invitationCode,
    ) {
    }
}
