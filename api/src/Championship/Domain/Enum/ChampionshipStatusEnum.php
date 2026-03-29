<?php

declare(strict_types=1);

namespace App\Championship\Domain\Enum;

enum ChampionshipStatusEnum: int
{
    case CREATED = 1;
    case BID_IN_PROGRESS = 2;
    case BID_RESULT_PROCESSED = 3;

    case NEED_TO_ASSIGN_RACES = 4;

    /**
     * After bid are done.
     */
    case ACTIVE = 5;
    case CANCELLED = 6;
    case CANCELLED_NOT_ENOUGH_RACES_LEFT = 7;
    case OVER = 8;

    /**
     * @return ChampionshipStatusEnum[]
     */
    public static function isActiveStatus(): array
    {
        return [self::CREATED, self::BID_IN_PROGRESS, self::BID_RESULT_PROCESSED, self::NEED_TO_ASSIGN_RACES, self::ACTIVE];
    }

    /**
     * @return ChampionshipStatusEnum[]
     */
    public static function isOverStatus(): array
    {
        return [self::CANCELLED, self::CANCELLED_NOT_ENOUGH_RACES_LEFT, self::OVER];
    }
}
