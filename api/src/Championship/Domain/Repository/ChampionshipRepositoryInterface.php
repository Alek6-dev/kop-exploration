<?php

declare(strict_types=1);

namespace App\Championship\Domain\Repository;

use App\Championship\Domain\Enum\ChampionshipStatusEnum;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Shared\Domain\Repository\CrudRepositoryInterface;
use App\Shared\Domain\Repository\RepositoryInterface;
use App\User\Domain\Model\UserVisitorInterface;

interface ChampionshipRepositoryInterface extends RepositoryInterface, CrudRepositoryInterface
{
    /**
     * @return array<string>
     */
    public function getInvitationCodes(): array;

    public function withInvitationCode(string $invitationCode): static;

    public function getByInvitationCode(string $invitationCode): ?ChampionshipInterface;

    public function withStatus(ChampionshipStatusEnum $status, bool $orCondition = false): static;

    public function withUser(UserVisitorInterface $user): static;

    public function withPlayerSlotsAreFull(): static;

    public function groupByChampionship(): static;

    public function withCurrentRoundEndDateLessThan(?\DateTimeImmutable $date): static;

    public function withBettingRoundOver(?\DateTimeImmutable $date): static;

    public function withLimitStrategyDate(?\DateTimeImmutable $date): static;

    /**
     * @param array<ChampionshipStatusEnum> $statuses
     */
    public function withStatuses(array $statuses): static;
}
