<?php

declare(strict_types=1);

namespace App\SeasonGame\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Team\Infrastructure\Doctrine\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;

final readonly class SeasonAvailableTeamsProvider implements ProviderInterface
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        /** @var Team[] $teams */
        $teams = $this->em->createQuery(
            'SELECT t FROM App\Team\Infrastructure\Doctrine\Entity\Team t
             ORDER BY t.name ASC'
        )->getResult();

        return array_map(fn (Team $t) => [
            'uuid' => $t->getUuid(),
            'name' => $t->getName(),
            'minValue' => $t->getMinValue(),
            'color' => $t->getColor(),
            'image' => $t->getRelativeImagePath(),
        ], $teams);
    }
}
