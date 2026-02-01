<?php

declare(strict_types=1);

namespace App\Parameter\Application\Query\Get;

use App\Parameter\Domain\Model\ParameterInterface;
use App\Shared\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<ParameterInterface>
 */
final readonly class GetParameterQuery implements QueryInterface
{
    public function __construct(
        public string $code,
    ) {
    }
}
