<?php

declare(strict_types=1);

namespace App\SeasonGame\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\SeasonGame\Application\Command\EnrollInSeason\EnrollInSeasonCommand;
use App\SeasonGame\Infrastructure\ApiPlatform\Resource\SeasonParticipationResource;
use App\Shared\Application\Command\CommandBusInterface;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class EnrollInSeasonProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private Security $security,
        private EntityManagerInterface $em,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): SeasonParticipationResource
    {
        /** @var UserVisitor $user */
        $user = $this->security->getUser();

        $participation = $this->commandBus->dispatch(new EnrollInSeasonCommand($user));

        $this->em->persist($participation);
        $this->em->flush();

        return SeasonParticipationResource::fromModel($participation);
    }
}
