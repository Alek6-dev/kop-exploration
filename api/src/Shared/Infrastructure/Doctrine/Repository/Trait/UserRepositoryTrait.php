<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine\Repository\Trait;

use App\Shared\Domain\Enum\User\StatusEnum;
use Doctrine\ORM\QueryBuilder;

trait UserRepositoryTrait
{
    public function withStatus(StatusEnum $status): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($status): void {
            $qb
                ->andWhere(sprintf('%s.status = :status', self::ALIAS))
                ->setParameter(':status', $status)
            ;
        });
    }
}
