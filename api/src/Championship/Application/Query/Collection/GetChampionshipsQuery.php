<?php

declare(strict_types=1);

namespace App\Championship\Application\Query\Collection;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Shared\Application\Query\QueryInterface;
use App\User\Domain\Model\UserVisitorInterface;

/**
 * @implements QueryInterface<ChampionshipInterface>
 */
final readonly class GetChampionshipsQuery implements QueryInterface
{
    public function __construct(
        public bool $isActive,
        public UserVisitorInterface $user,
        public ?int $page = null,
        public ?int $itemsPerPage = null,
    ) {
    }
}
