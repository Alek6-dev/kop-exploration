<?php

declare(strict_types=1);

namespace App\Championship\Application\Command\Delete;

use App\Championship\Domain\Enum\ChampionshipStatusEnum;
use App\Championship\Domain\Exception\ChampionshipException;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Championship\Domain\Repository\ChampionshipRepositoryInterface;
use App\Shared\Application\Command\AsCommandHandler;

#[AsCommandHandler]
final readonly class DeleteChampionshipCommandHandler
{
    public function __construct(private ChampionshipRepositoryInterface $repository)
    {
    }

    public function __invoke(DeleteChampionshipCommand $command): ChampionshipInterface
    {
        /** @var ?ChampionshipInterface $model */
        $model = $this->repository->getByUuid($command->uuid);

        if (!$model) {
            throw ChampionshipException::notFound($command->uuid);
        }
        if (!$command->isSystem) {
            if ($model->getCreatedBy() !== $command->user) {
                throw ChampionshipException::wrongCreatorToCancel();
            }

            if (ChampionshipStatusEnum::CREATED !== $model->getStatus()) {
                throw ChampionshipException::cantBeCancelled($command->uuid);
            }
        }

        $model->setStatus(ChampionshipStatusEnum::CANCELLED);

        return $model;
    }
}
