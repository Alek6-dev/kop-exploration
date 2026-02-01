<?php

declare(strict_types=1);

namespace App\Cosmetic\Application\Command\SelectCosmetic;

use App\Cosmetic\Domain\Exception\CosmeticException;
use App\Cosmetic\Domain\Model\CosmeticPossessedInterface;
use App\Cosmetic\Domain\Repository\CosmeticPossessedRepositoryInterface;
use App\Shared\Application\Command\AsCommandHandler;
use Doctrine\ORM\EntityManagerInterface;

#[AsCommandHandler]
final readonly class SelectCosmeticCommandHandler
{
    public function __construct(
        private CosmeticPossessedRepositoryInterface $cosmeticPossessedRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(SelectCosmeticCommand $command): CosmeticPossessedInterface
    {
        /** @var ?CosmeticPossessedInterface $cosmeticPossessed */
        $cosmeticPossessed = $this->cosmeticPossessedRepository
            ->withCosmetic($command->cosmetic)
            ->withUser($command->user)
            ->first()
        ;

        if (!$cosmeticPossessed) {
            throw CosmeticException::notPossessed($command->user->getUuid(), $command->cosmetic->getUuid());
        }

        /** @var ?CosmeticPossessedInterface $cosmeticAlreadySelected */
        $cosmeticAlreadySelected = $this->cosmeticPossessedRepository
            ->withUser($command->user)
            ->withTypeCosmetic($command->cosmetic->getType())
            ->withIsSelected(true)
            ->first()
        ;

        if ($cosmeticAlreadySelected) {
            $cosmeticAlreadySelected->setIsSelected(false);
            $this->entityManager->persist($cosmeticAlreadySelected);
        }

        $cosmeticPossessed->setIsSelected(true);
        $this->entityManager->persist($cosmeticPossessed);

        $this->entityManager->flush();

        return $cosmeticPossessed;
    }
}
