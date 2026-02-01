<?php

declare(strict_types=1);

namespace App\User\Application\Query\Stat\CountChampionships;

use App\Championship\Domain\Enum\ChampionshipStatusEnum;
use App\Player\Domain\Repository\PlayerRepositoryInterface;
use App\Shared\Application\Query\AsQueryHandler;
use App\User\Domain\Exception\UserVisitorException;
use App\User\Domain\Model\UserVisitorInterface;
use App\User\Domain\Repository\UserVisitorRepositoryInterface;

#[AsQueryHandler]
final readonly class CountChampionshipsQueryHandler
{
    public function __construct(
        private UserVisitorRepositoryInterface $userVisitorRepository,
        private PlayerRepositoryInterface $repository
    ) {
    }

    public function __invoke(CountChampionshipsQuery $query): int
    {
        /** @var ?UserVisitorInterface $user */
        $user = $this->userVisitorRepository->getByUuid($query->userUuid);

        if (!$user) {
            UserVisitorException::notFound($query->userUuid);
        }

        $players = $this->repository
            ->withUser($user)
            ->withChampionshipStatus(ChampionshipStatusEnum::OVER)
        ;

        if ($query->position) {
            $players = $players->withPosition($query->position);
        }

        return $players->count();
    }
}
