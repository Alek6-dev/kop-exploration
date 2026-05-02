<?php

declare(strict_types=1);

namespace App\SeasonGame\Application\Query\GetSeasonParticipation;

use App\Shared\Application\Query\QueryInterface;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;

/**
 * @implements QueryInterface<self>
 */
class GetSeasonParticipationQuery implements QueryInterface
{
    public function __construct(
        public UserVisitor $user,
    ) {
    }
}
