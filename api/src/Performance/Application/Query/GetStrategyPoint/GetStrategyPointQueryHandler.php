<?php

declare(strict_types=1);

namespace App\Performance\Application\Query\GetStrategyPoint;

use App\Championship\Domain\Enum\ChampionshipNumberPlayerEnum;
use App\Performance\Domain\Enum\StrategyPosition\FourPlayersEnum;
use App\Performance\Domain\Enum\StrategyPosition\HeightPlayersEnum;
use App\Performance\Domain\Enum\StrategyPosition\SixPlayersEnum;
use App\Performance\Domain\Enum\StrategyPosition\TenPlayersEnum;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class GetStrategyPointQueryHandler
{
    public function __invoke(GetStrategyPointQuery $query): int
    {
        $points = 0;
        switch ($query->championship->getNumberOfPlayers()) {
            case ChampionshipNumberPlayerEnum::FOUR_PLAYERS:
                $points = FourPlayersEnum::getPointsFromPosition($query->position)->value;
                break;
            case ChampionshipNumberPlayerEnum::SIX_PLAYERS:
                $points = SixPlayersEnum::getPointsFromPosition($query->position)->value;
                break;
            case ChampionshipNumberPlayerEnum::HEIGHT_PLAYERS:
                $points = HeightPlayersEnum::getPointsFromPosition($query->position)->value;
                break;
            case ChampionshipNumberPlayerEnum::TEN_PLAYERS:
                $points = TenPlayersEnum::getPointsFromPosition($query->position)->value;
                break;
        }

        return $points;
    }
}
