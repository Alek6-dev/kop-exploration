<?php

declare(strict_types=1);

namespace App\Cosmetic\Application\Command\AddDefaultCosmetics;

use App\Cosmetic\Domain\Model\CosmeticInterface;
use App\Cosmetic\Domain\Repository\CosmeticRepositoryInterface;
use App\Cosmetic\Infrastructure\Doctrine\Entity\CosmeticPossessed;
use App\Shared\Application\Command\AsCommandHandler;
use App\User\Domain\Model\UserVisitorInterface;

#[AsCommandHandler]
final readonly class AddDefaultCosmeticsCommandHandler
{
    public function __construct(
        private CosmeticRepositoryInterface $repository,
    ) {
    }

    public function __invoke(AddDefaultCosmeticsCommand $command): UserVisitorInterface
    {
        $user = $command->user;
        $cosmetics = $this->repository->withIsDefault(true);

        $cosmeticsToAttach = [];
        /** @var CosmeticInterface $cosmetic */
        foreach ($cosmetics as $cosmetic) {
            $cosmeticsToAttach[$cosmetic->getType()->value][] = (new CosmeticPossessed())
                ->setUser($user)
                ->setCosmetic($cosmetic)
                ->setPrice(0)
            ;
        }

        foreach ($cosmeticsToAttach as $cosmeticsByType) {
            $randomCosmeticKey = array_rand($cosmeticsByType);
            $cosmeticsByType[$randomCosmeticKey]->setIsSelected(true);
        }

        return $user;
    }
}
