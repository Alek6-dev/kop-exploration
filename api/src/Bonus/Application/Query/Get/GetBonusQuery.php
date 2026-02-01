<?php

declare(strict_types=1);

namespace App\Bonus\Application\Query\Get;

use App\Bonus\Domain\Model\BonusInterface;
use App\Shared\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<BonusInterface>
 */
final readonly class GetBonusQuery implements QueryInterface
{
    public function __construct(
        public string $uuid,
    ) {
    }
}
