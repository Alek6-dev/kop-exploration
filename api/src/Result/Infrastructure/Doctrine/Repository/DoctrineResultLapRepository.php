<?php

declare(strict_types=1);

namespace App\Result\Infrastructure\Doctrine\Repository;

use App\Result\Domain\Repository\ResultLapRepositoryInterface;
use App\Result\Infrastructure\Doctrine\Entity\ResultLap;
use App\Shared\Infrastructure\Doctrine\DoctrineRepository;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\CrudRepositoryTrait;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineResultLapRepository extends DoctrineRepository implements ResultLapRepositoryInterface
{
    use CrudRepositoryTrait;
    private const string ENTITY_CLASS = ResultLap::class;
    private const string ALIAS = 'result_lap';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }
}
