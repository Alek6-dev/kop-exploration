<?php

declare(strict_types=1);

namespace App\Bid\Infrastructure\Doctrine\Repository;

use App\Bid\Domain\Repository\BettingRoundDriverRepositoryInterface;
use App\Bid\Infrastructure\Doctrine\Entity\BettingRoundDriver;
use App\Shared\Infrastructure\Doctrine\DoctrineRepository;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\CrudRepositoryTrait;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineBettingRoundDriverRepository extends DoctrineRepository implements BettingRoundDriverRepositoryInterface
{
    use CrudRepositoryTrait;
    private const string ENTITY_CLASS = BettingRoundDriver::class;
    private const string ALIAS = 'betting_round_driver';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }
}
