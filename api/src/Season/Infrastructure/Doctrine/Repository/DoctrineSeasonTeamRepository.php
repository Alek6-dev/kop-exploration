<?php

declare(strict_types=1);

namespace App\Season\Infrastructure\Doctrine\Repository;

use App\Season\Domain\Repository\SeasonTeamRepositoryInterface;
use App\Season\Infrastructure\Doctrine\Entity\SeasonTeam;
use App\Shared\Infrastructure\Doctrine\DoctrineRepository;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\CrudRepositoryTrait;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineSeasonTeamRepository extends DoctrineRepository implements SeasonTeamRepositoryInterface
{
    use CrudRepositoryTrait;
    private const string ENTITY_CLASS = SeasonTeam::class;
    private const string ALIAS = 'season_team';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }
}
