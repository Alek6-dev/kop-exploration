<?php

declare(strict_types=1);

namespace App\SeasonGame\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\SeasonGame\Application\Command\SaveSeasonGPStrategy\SaveSeasonGPStrategyCommand;
use App\SeasonGame\Application\Dto\SaveSeasonGPStrategyDto;
use App\SeasonGame\Application\Query\GetSeasonParticipation\GetSeasonParticipationQuery;
use App\SeasonGame\Domain\Exception\SeasonGameException;
use App\SeasonGame\Infrastructure\ApiPlatform\Resource\SeasonGPStrategyResource;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Query\QueryBusInterface;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class SaveSeasonGPStrategyProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
        private Security $security,
        private EntityManagerInterface $em,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): SeasonGPStrategyResource
    {
        /** @var UserVisitor $user */
        $user = $this->security->getUser();

        /** @var SaveSeasonGPStrategyDto $data */
        $participation = $this->queryBus->ask(new GetSeasonParticipationQuery($user));

        if (!$participation) {
            throw SeasonGameException::rosterNotFound();
        }

        $strategy = $this->commandBus->dispatch(new SaveSeasonGPStrategyCommand(
            $participation,
            $uriVariables['raceUuid'],
            $data->driver1Uuid,
            $data->driver2Uuid,
            $data->teamUuid,
        ));

        $this->em->persist($strategy);
        $this->em->flush();

        return SeasonGPStrategyResource::fromModel($strategy);
    }
}
