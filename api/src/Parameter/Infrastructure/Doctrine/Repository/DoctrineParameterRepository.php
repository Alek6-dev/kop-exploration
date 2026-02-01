<?php

declare(strict_types=1);

namespace App\Parameter\Infrastructure\Doctrine\Repository;

use App\Parameter\Domain\Model\ParameterInterface;
use App\Parameter\Domain\Repository\ParameterRepositoryInterface;
use App\Parameter\Infrastructure\Doctrine\Entity\Parameter;
use App\Shared\Infrastructure\Doctrine\DoctrineRepository;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\CrudRepositoryTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class DoctrineParameterRepository extends DoctrineRepository implements ParameterRepositoryInterface
{
    use CrudRepositoryTrait;
    private const string ENTITY_CLASS = Parameter::class;
    private const string ALIAS = 'parameter';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function getParameterByCode(string $code): ?ParameterInterface
    {
        $parameter = $this->withCode($code)->query()
            ->getQuery()
            ->getOneOrNullResult();

        return $parameter;
    }

    public function withCode(string $code): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($code): void {
            $qb->where(sprintf('%s.code = :code', self::ALIAS))->setParameter('code', $code);
        });
    }

    public function getAll(): ?array
    {
        return $this->query()->getQuery()->getResult();
    }
}
