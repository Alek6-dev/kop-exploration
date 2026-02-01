<?php

declare(strict_types=1);

namespace App\Championship\Application\Query\InvitationCodes;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Shared\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<ChampionshipInterface>
 */
final readonly class GetChampionshipInvitationCodesQuery implements QueryInterface
{
    public function __construct(
    ) {
    }
}
