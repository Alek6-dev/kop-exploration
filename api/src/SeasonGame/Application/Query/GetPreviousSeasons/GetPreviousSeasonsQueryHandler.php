<?php

declare(strict_types=1);

namespace App\SeasonGame\Application\Query\GetPreviousSeasons;

use App\Season\Infrastructure\Doctrine\Entity\Season;
use App\Shared\Application\Query\AsQueryHandler;
use Doctrine\ORM\EntityManagerInterface;

#[AsQueryHandler]
final readonly class GetPreviousSeasonsQueryHandler
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function __invoke(GetPreviousSeasonsQuery $query): array
    {
        return $this->em->createQuery(
            'SELECT s FROM App\Season\Infrastructure\Doctrine\Entity\Season s
             WHERE s.isActive = false
             ORDER BY s.createdAt DESC'
        )->getResult();
    }
}
