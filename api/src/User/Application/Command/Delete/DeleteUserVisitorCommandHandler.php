<?php

declare(strict_types=1);

namespace App\User\Application\Command\Delete;

use App\Shared\Application\Command\AsCommandHandler;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Command\Token\GenerateUniqueTokenCommand;
use App\Shared\Domain\Enum\User\StatusEnum;
use App\User\Domain\Exception\UserVisitorException;
use App\User\Domain\Model\UserVisitorInterface;
use App\User\Domain\Repository\UserVisitorRepositoryInterface;
use Symfony\Bundle\SecurityBundle\Security;

#[AsCommandHandler]
final readonly class DeleteUserVisitorCommandHandler
{
    public function __construct(
        private UserVisitorRepositoryInterface $repository,
        private CommandBusInterface $commandBus,
        private Security $security,
    ) {
    }

    public function __invoke(DeleteUserVisitorCommand $command): void
    {
        /** @var UserVisitorInterface $user */
        $user = $this->security->getUser();
        if ($command->uuid !== $user->getUuid()) {
            throw UserVisitorException::notAllowedToDeleteUser();
        }

        /** @var ?UserVisitorInterface $model */
        $model = $this->repository->getByUuid($command->uuid);

        if (!$model) {
            throw UserVisitorException::notFound($command->uuid);
        }

        $pseudos = [];
        /** @var UserVisitorInterface $user */
        foreach ($this->repository as $user) {
            $pseudos[] = $user->getPseudo();
        }

        $pseudo = 'anonyme-'.$this->commandBus->dispatch(new GenerateUniqueTokenCommand(
            8,
            $pseudos
        ));
        $model
            ->setPseudo($pseudo)
            ->setEmail(sprintf('%s@%s', $pseudo, 'ano.com'))
            ->setStatus(StatusEnum::DELETED)
        ;
    }
}
