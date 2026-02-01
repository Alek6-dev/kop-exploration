<?php

declare(strict_types=1);

namespace App\Cosmetic\Application\Query\Collection;

use App\Cosmetic\Domain\Enum\TypeCosmeticEnum;
use App\Cosmetic\Domain\Model\CosmeticInterface;
use App\Shared\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<CosmeticInterface>
 */
final readonly class GetCosmeticsQuery implements QueryInterface
{
    public function __construct(
        public ?string $name,
        public ?TypeCosmeticEnum $type,
        public ?int $page = null,
        public ?int $itemsPerPage = null,
    ) {
    }
}
