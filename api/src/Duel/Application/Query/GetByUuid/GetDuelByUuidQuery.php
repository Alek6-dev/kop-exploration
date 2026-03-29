<?php

declare(strict_types=1);

namespace App\Duel\Application\Query\GetByUuid;

use App\Duel\Domain\Model\DuelInterface;
use App\Shared\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<DuelInterface>
 */
final readonly class GetDuelByUuidQuery implements QueryInterface
{
    public function __construct(
        public string $uuid,
    ) {
    }
}
