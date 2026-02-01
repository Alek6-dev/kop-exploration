<?php

declare(strict_types=1);

namespace App\Cosmetic\Application\Query\Collection;

use App\Cosmetic\Domain\Repository\CosmeticRepositoryInterface;
use App\Shared\Application\Query\AsQueryHandler;
use App\User\Domain\Model\UserVisitorInterface;
use Symfony\Bundle\SecurityBundle\Security;

#[AsQueryHandler]
final readonly class GetCosmeticsQueryHandler
{
    public function __construct(
        private CosmeticRepositoryInterface $cosmeticRepository,
        private Security $security,
    ) {
    }

    public function __invoke(GetCosmeticsQuery $query): CosmeticRepositoryInterface
    {
        $cosmeticRepository = $this->cosmeticRepository;

        /** @var UserVisitorInterface $user */
        $user = $this->security->getUser();

        if (null !== $query->name) {
            $cosmeticRepository = $cosmeticRepository->withName($query->name);
        }

        if (null !== $query->type) {
            $cosmeticRepository = $cosmeticRepository->withType($query->type);
        }
        $cosmeticRepository = $cosmeticRepository->withOrderByPossessed($user);

        if (null !== $query->page && null !== $query->itemsPerPage) {
            $cosmeticRepository = $cosmeticRepository->withPagination($query->page, $query->itemsPerPage);
        }

        return $cosmeticRepository;
    }
}
