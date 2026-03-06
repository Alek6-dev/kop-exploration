<?php

declare(strict_types=1);

use App\Performance\Domain\Enum\QualificationPositionPointEnum;
use App\Performance\Domain\Enum\RacePositionPointEnum;
use App\Performance\Domain\Enum\SprintPositionPointEnum;
use App\Performance\Domain\Enum\TeamMultiplierEnum;

// --- Team Multiplier (11 écuries) ---

test('team multiplier returns correct values for positions 1 to 10', function (int $position, int $expectedMultiplier) {
    expect(TeamMultiplierEnum::getPointsFromPosition((string) $position)->value)
        ->toBe($expectedMultiplier);
})->with([
    'Position 1 => x2.0' => [1, 20],
    'Position 2 => x1.9' => [2, 19],
    'Position 3 => x1.8' => [3, 18],
    'Position 4 => x1.7' => [4, 17],
    'Position 5 => x1.6' => [5, 16],
    'Position 6 => x1.5' => [6, 15],
    'Position 7 => x1.4' => [7, 14],
    'Position 8 => x1.3' => [8, 13],
    'Position 9 => x1.2' => [9, 12],
    'Position 10 => x1.1' => [10, 11],
]);

test('team multiplier returns x1.0 for position 11 (11th team)', function () {
    expect(TeamMultiplierEnum::getPointsFromPosition('11')->value)
        ->toBe(10);
});

test('team multiplier returns x1.0 for any position beyond 11', function (int $position) {
    expect(TeamMultiplierEnum::getPointsFromPosition((string) $position)->value)
        ->toBe(10);
})->with([12, 15, 99]);

// --- Qualification Scoring (22 pilotes) ---

test('qualification scoring returns correct points for positions 1 to 15', function (int $position, int $expectedPoints) {
    expect(QualificationPositionPointEnum::getPointsFromPosition((string) $position)->value)
        ->toBe($expectedPoints);
})->with([
    'P1 => 15 pts' => [1, 15],
    'P2 => 14 pts' => [2, 14],
    'P3 => 13 pts' => [3, 13],
    'P4 => 12 pts' => [4, 12],
    'P5 => 11 pts' => [5, 11],
    'P6 => 10 pts' => [6, 10],
    'P7 => 9 pts' => [7, 9],
    'P8 => 8 pts' => [8, 8],
    'P9 => 7 pts' => [9, 7],
    'P10 => 6 pts' => [10, 6],
    'P11 => 5 pts' => [11, 5],
    'P12 => 4 pts' => [12, 4],
    'P13 => 3 pts' => [13, 3],
    'P14 => 2 pts' => [14, 2],
    'P15 => 1 pt' => [15, 1],
]);

test('qualification scoring returns 0 points for positions 16 to 22', function (int $position) {
    expect(QualificationPositionPointEnum::getPointsFromPosition((string) $position)->value)
        ->toBe(0);
})->with([16, 17, 18, 19, 20, 21, 22]);

// --- Race Scoring (22 pilotes) ---

test('race scoring returns correct points for positions 1 to 15', function (int $position, int $expectedPoints) {
    expect(RacePositionPointEnum::getPointsFromPosition($position)->value)
        ->toBe($expectedPoints);
})->with([
    'P1 => 25 pts' => [1, 25],
    'P2 => 22 pts' => [2, 22],
    'P3 => 20 pts' => [3, 20],
    'P4 => 18 pts' => [4, 18],
    'P5 => 16 pts' => [5, 16],
    'P6 => 14 pts' => [6, 14],
    'P7 => 12 pts' => [7, 12],
    'P8 => 10 pts' => [8, 10],
    'P9 => 8 pts' => [9, 8],
    'P10 => 6 pts' => [10, 6],
    'P11 => 5 pts' => [11, 5],
    'P12 => 4 pts' => [12, 4],
    'P13 => 3 pts' => [13, 3],
    'P14 => 2 pts' => [14, 2],
    'P15 => 1 pt' => [15, 1],
]);

test('race scoring returns 0 points for positions 16 to 22', function (int $position) {
    expect(RacePositionPointEnum::getPointsFromPosition($position)->value)
        ->toBe(0);
})->with([16, 17, 18, 19, 20, 21, 22]);

// --- Sprint Scoring (22 pilotes) ---

test('sprint scoring returns correct points for positions 1 to 8', function (int $position, int $expectedPoints) {
    expect(SprintPositionPointEnum::getPointsFromPosition((string) $position)->value)
        ->toBe($expectedPoints);
})->with([
    'P1 => 12 pts' => [1, 12],
    'P2 => 10 pts' => [2, 10],
    'P3 => 8 pts' => [3, 8],
    'P4 => 6 pts' => [4, 6],
    'P5 => 4 pts' => [5, 4],
    'P6 => 3 pts' => [6, 3],
    'P7 => 2 pts' => [7, 2],
    'P8 => 1 pt' => [8, 1],
]);

test('sprint scoring returns 0 points for positions 9 to 22', function (int $position) {
    expect(SprintPositionPointEnum::getPointsFromPosition((string) $position)->value)
        ->toBe(0);
})->with([9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22]);
