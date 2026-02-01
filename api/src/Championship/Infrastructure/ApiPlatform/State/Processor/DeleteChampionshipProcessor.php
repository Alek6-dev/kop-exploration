<?php

declare(strict_types=1);

namespace App\Championship\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Championship\Application\Command\Delete\DeleteChampionshipCommand;
use App\Shared\Application\Command\CommandBusInterface;
use App\User\Domain\Model\UserVisitorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProcessorInterface<false, void>
 */
final readonly class DeleteChampionshipProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private Security $security,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        /** @var UserVisitorInterface $user */
        $user = $this->security->getUser();

        $championship = $this->commandBus->dispatch(new DeleteChampionshipCommand(
            $uriVariables['uuid'],
            $user,
        ));

        $this->entityManager->persist($championship);

        $this->entityManager->flush();
    }
}
