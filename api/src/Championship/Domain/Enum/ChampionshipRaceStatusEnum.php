<?php

declare(strict_types=1);

namespace App\Championship\Domain\Enum;

enum ChampionshipRaceStatusEnum: int
{
    case CREATED = 1;
    case ACTIVE = 2;
    case WAITING_RESULT = 3;
    case RESULT_PROCESSED = 4;
    case OVER = 5;

    /**
     * @return ChampionshipRaceStatusEnum[]
     */
    public static function isActiveStatus(): array
    {
        return [self::ACTIVE, self::WAITING_RESULT, self::RESULT_PROCESSED];
    }

    /**
     * @return ChampionshipRaceStatusEnum[]
     */
    public static function isNotActiveStatus(): array
    {
        return [self::CREATED, self::OVER];
    }
}
