<?php

declare(strict_types=1);

namespace App\Bonus\Application\Query\Collection;

use App\Bonus\Domain\Enum\BonusTypeEnum;
use App\Bonus\Domain\Model\BonusInterface;
use App\Shared\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<array<BonusInterface>>
 */
final readonly class GetBonusCollectionQuery implements QueryInterface
{
    public function __construct(
        public BonusTypeEnum $type,
        public bool $isJoker,
        public ?int $page = null,
        public ?int $itemsPerPage = null,
    ) {
    }
}
