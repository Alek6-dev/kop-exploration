<?php

declare(strict_types=1);

namespace App\Cosmetic\Application\Query\Get;

use App\Cosmetic\Domain\Model\CosmeticInterface;
use App\Shared\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<CosmeticInterface>
 */
final readonly class GetCosmeticQuery implements QueryInterface
{
    public function __construct(
        public string $uuid,
    ) {
    }
}
