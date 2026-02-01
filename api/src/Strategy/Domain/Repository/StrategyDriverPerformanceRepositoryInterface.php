<?php

declare(strict_types=1);

namespace App\Strategy\Domain\Repository;

use App\Shared\Domain\Repository\CrudRepositoryInterface;
use App\Shared\Domain\Repository\RepositoryInterface;

interface StrategyDriverPerformanceRepositoryInterface extends RepositoryInterface, CrudRepositoryInterface
{
}
