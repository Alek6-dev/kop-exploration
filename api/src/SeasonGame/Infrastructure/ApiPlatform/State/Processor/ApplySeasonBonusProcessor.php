<?php

declare(strict_types=1);

namespace App\SeasonGame\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\SeasonGame\Application\Command\ApplySeasonBonus\ApplySeasonBonusCommand;
use App\SeasonGame\Application\Dto\ApplySeasonBonusDto;
use App\SeasonGame\Application\Query\GetSeasonParticipation\GetSeasonParticipationQuery;
use App\SeasonGame\Domain\Exception\SeasonGameException;
use App\SeasonGame\Infrastructure\ApiPlatform\Resource\SeasonParticipationResource;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Query\QueryBusInterface;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class ApplySeasonBonusProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
        private Security $security,
        private EntityManagerInterface $em,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): SeasonParticipationResource
    {
        /** @var UserVisitor $user */
        $user = $this->security->getUser();

        /** @var ApplySeasonBonusDto $data */
        $participation = $this->queryBus->ask(new GetSeasonParticipationQuery($user));

        if (!$participation) {
            throw SeasonGameException::rosterNotFound();
        }

        $bonusUsage = $this->commandBus->dispatch(new ApplySeasonBonusCommand(
            $participation,
            $uriVariables['raceUuid'],
            $data->bonusType,
        ));

        $this->em->persist($bonusUsage);
        $this->em->flush();

        return SeasonParticipationResource::fromModel($participation);
    }
}
