<?php

declare(strict_types=1);

namespace App\Strategy\Application\Query\GetByUuid;

use App\Shared\Application\Query\QueryInterface;
use App\Strategy\Domain\Model\StrategyInterface;

/**
 * @implements QueryInterface<StrategyInterface>
 */
final readonly class GetStrategyByUuidQuery implements QueryInterface
{
    public function __construct(
        public string $uuid,
    ) {
    }
}
