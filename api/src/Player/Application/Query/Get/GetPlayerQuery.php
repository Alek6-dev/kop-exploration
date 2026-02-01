<?php

declare(strict_types=1);

namespace App\Player\Application\Query\Get;

use App\Player\Domain\Model\PlayerInterface;
use App\Shared\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<PlayerInterface>
 */
final readonly class GetPlayerQuery implements QueryInterface
{
    public function __construct(
        public string $uuid,
    ) {
    }
}
