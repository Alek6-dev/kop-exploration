<?php

declare(strict_types=1);

namespace App\Championship\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Championship\Application\Command\Start\StartChampionshipCommand;
use App\Championship\Application\Query\Get\GetChampionshipQuery;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Championship\Infrastructure\ApiPlatform\Resource\ChampionshipResource;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Query\QueryBusInterface;
use App\User\Domain\Model\UserVisitorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProcessorInterface<false, ChampionshipResource>
 */
final readonly class StartChampionshipProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
        private Security $security,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ChampionshipResource
    {
        $uuid = $uriVariables['uuid'];

        /** @var UserVisitorInterface $user */
        $user = $this->security->getUser();

        /** @var ChampionshipInterface $championship */
        $championship = $this->queryBus->ask(new GetChampionshipQuery(
            $uuid,
        ));

        /** @var ChampionshipInterface $championship */
        $championship = $this->commandBus->dispatch(new StartChampionshipCommand(
            $championship,
            $user,
        ));

        $this->entityManager->persist($championship);

        $this->entityManager->flush();

        return ChampionshipResource::fromModel($championship);
    }
}
