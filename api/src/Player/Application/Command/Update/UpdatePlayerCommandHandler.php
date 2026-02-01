<?php

declare(strict_types=1);

namespace App\Player\Application\Command\Update;

use App\Player\Domain\Exception\PlayerException;
use App\Player\Domain\Model\PlayerInterface;
use App\Player\Domain\Repository\PlayerRepositoryInterface;
use App\Shared\Application\Command\AsCommandHandler;

#[AsCommandHandler]
final readonly class UpdatePlayerCommandHandler
{
    public function __construct(private PlayerRepositoryInterface $repository)
    {
    }

    public function __invoke(UpdatePlayerCommand $command): PlayerInterface
    {
        /** @var ?PlayerInterface $model */
        $model = $this->repository->getByUuid($command->uuid);

        if (!$model) {
            throw PlayerException::notFound($command->uuid);
        }

        $model
            ->setSelectedTeam($command->selectedTeam ?? $model->getSelectedTeam())
            ->setSelectedDriver1($command->selectDriver1 ?? $model->getSelectedDriver1())
            ->setSelectedDriver2($command->selectDriver2 ?? $model->getSelectedDriver2())
        ;

        $this->repository->update($model);

        return $model;
    }
}
