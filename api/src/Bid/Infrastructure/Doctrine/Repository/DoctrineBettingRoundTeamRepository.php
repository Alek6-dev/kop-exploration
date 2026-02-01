<?php

declare(strict_types=1);

namespace App\Bid\Infrastructure\Doctrine\Repository;

use App\Bid\Domain\Repository\BettingRoundTeamRepositoryInterface;
use App\Bid\Infrastructure\Doctrine\Entity\BettingRoundTeam;
use App\Shared\Infrastructure\Doctrine\DoctrineRepository;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\CrudRepositoryTrait;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineBettingRoundTeamRepository extends DoctrineRepository implements BettingRoundTeamRepositoryInterface
{
    use CrudRepositoryTrait;
    private const string ENTITY_CLASS = BettingRoundTeam::class;
    private const string ALIAS = 'betting_round_team';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }
}
