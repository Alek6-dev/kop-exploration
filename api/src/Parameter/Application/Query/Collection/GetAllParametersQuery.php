<?php

declare(strict_types=1);

namespace App\Parameter\Application\Query\Collection;

use App\Parameter\Domain\Model\ParameterInterface;
use App\Shared\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<ParameterInterface>
 */
final readonly class GetAllParametersQuery implements QueryInterface
{
}
