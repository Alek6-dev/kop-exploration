<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Console\F1;

use App\Driver\Domain\Model\DriverInterface;
use App\Driver\Domain\Repository\DriverRepositoryInterface;
use App\Performance\Application\Command\SavePerformance\SaveDriverPerformanceCommand;
use App\Performance\Application\Command\SavePerformance\SaveTeamPerformanceCommand;
use App\Performance\Domain\Enum\TeamMultiplierEnum;
use App\Performance\Domain\Model\DriverPerformanceInterface;
use App\Performance\Domain\Model\TeamPerformanceInterface;
use App\Race\Domain\Repository\RaceRepositoryInterface;
use App\Result\Domain\Repository\ResultRepositoryInterface;
use App\Result\Infrastructure\Doctrine\Entity\Result;
use App\Result\Infrastructure\Doctrine\Entity\ResultLap;
use App\Result\Domain\Enum\TypeResultEnum;
use App\Season\Domain\Repository\SeasonRaceRepositoryInterface;
use App\Season\Domain\Repository\SeasonRepositoryInterface;
use App\SeasonGame\Application\Command\ComputeSeasonGPScores\ComputeSeasonGPScoresCommand;
use App\Shared\Application\Command\CommandBusInterface;
use App\Team\Domain\Model\TeamInterface;
use App\Team\Domain\Repository\TeamRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'kop:f1:import-race',
    description: 'Import F1 race results (qualifying, sprint, race) into KOP.',
)]
final class ImportRaceCommand extends Command
{
    private const string BASE_URL = 'https://livetiming.formula1.com/static';

    public function __construct(
        private readonly SeasonRepositoryInterface $seasonRepository,
        private readonly SeasonRaceRepositoryInterface $seasonRaceRepository,
        private readonly RaceRepositoryInterface $raceRepository,
        private readonly ResultRepositoryInterface $resultRepository,
        private readonly DriverRepositoryInterface $driverRepository,
        private readonly TeamRepositoryInterface $teamRepository,
        private readonly CommandBusInterface $commandBus,
        private readonly EntityManagerInterface $em,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('year', InputArgument::REQUIRED, 'F1 season year (e.g. 2026)')
            ->addArgument('gp', InputArgument::REQUIRED, 'GP number in the season (e.g. 1)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $year = (int) $input->getArgument('year');
        $gpNumber = (int) $input->getArgument('gp');

        $io->title(sprintf('Importing F1 %d — GP #%d', $year, $gpNumber));

        // 1. Fetch Index.json and find the target meeting
        $index = $this->fetchJson(sprintf('%s/%d/Index.json', self::BASE_URL, $year));
        if (null === $index) {
            $io->error('Could not fetch Index.json.');

            return Command::FAILURE;
        }

        $meeting = $this->findGpMeeting($index['Meetings'] ?? [], $gpNumber);
        if (null === $meeting) {
            $io->error(sprintf('GP #%d not found in Index.json.', $gpNumber));

            return Command::FAILURE;
        }

        $meetingName = $meeting['Name'];
        $io->text(sprintf('Meeting: %s', $meetingName));

        // 2. Find Season
        $season = $this->seasonRepository->findOneBy(['name' => (string) $year]);
        if (null === $season) {
            $io->error(sprintf('Season "%d" not found. Run kop:f1:sync-season %d first.', $year, $year));

            return Command::FAILURE;
        }

        // 3. Find Race
        $race = $this->raceRepository->findOneBy(['name' => $meetingName]);
        if (null === $race) {
            $io->error(sprintf('Race "%s" not found. Run kop:f1:sync-season %d first.', $meetingName, $year));

            return Command::FAILURE;
        }

        // 4. Find SeasonRace (for laps update)
        $seasonRace = $this->seasonRaceRepository
            ->withSeason($season)
            ->withRace($race)
            ->first();

        // 5. Find or create Result (withRace must be called before withSeason to avoid query param conflict)
        $result = $this->resultRepository->withRace($race)->withSeason($season)->first();
        if (null === $result) {
            $result = (new Result())->setSeason($season)->setRace($race);
            $this->em->persist($result);
            $io->text('Created Result entity.');
        } else {
            // Purge existing ResultLaps to allow re-import
            foreach ($result->getResultLaps() as $lap) {
                $this->em->remove($lap);
            }
            $this->em->flush();
            $io->text('Cleared existing ResultLaps for re-import.');
        }

        // 6. Pre-load drivers and teams from DB
        $sessions = $meeting['Sessions'];
        $raceSession = $this->findSessionByType($sessions, 'race');
        $qualifyingSession = $this->findSessionByType($sessions, 'qualifying');
        $sprintSession = $this->findSessionByType($sessions, 'sprint');

        if (null === $raceSession || null === $qualifyingSession) {
            $io->error('Race or Qualifying session not found in meeting data.');

            return Command::FAILURE;
        }

        // Build driver lookup: TLA → Driver entity
        $driverList = $this->fetchJson(sprintf('%s/%sDriverList.json', self::BASE_URL, $raceSession['Path']));
        if (null === $driverList) {
            $io->error('Could not fetch DriverList.json.');

            return Command::FAILURE;
        }

        [$driverByNumber, $teamByNumber] = $this->buildDriverTeamLookups($driverList, $season, $io);

        // 7. Fetch qualifying positions
        $io->section('Qualifying');
        $qualPositions = $this->fetchTimingPositions($qualifyingSession['Path'], $io);
        if (null !== $qualPositions) {
            $this->createResultLaps($qualPositions, $driverByNumber, $teamByNumber, $result, TypeResultEnum::QUALIFICATION, $io);
            $io->text(sprintf('Created %d qualification ResultLaps.', \count($qualPositions)));
        }

        // 8. Fetch sprint positions (optional)
        $sprintPositions = null;
        if (null !== $sprintSession) {
            $io->section('Sprint');
            $sprintPositions = $this->fetchTimingPositions($sprintSession['Path'], $io);
            if (null !== $sprintPositions) {
                $this->createResultLaps($sprintPositions, $driverByNumber, $teamByNumber, $result, TypeResultEnum::SPRINT, $io);
                $io->text(sprintf('Created %d sprint ResultLaps.', \count($sprintPositions)));
            }
        }

        // 9. Fetch race positions (final + lap by lap)
        $io->section('Race');
        [$racePositions, $raceFinished] = $this->fetchRaceFinalClassification($raceSession['Path'], $io);
        if (null === $racePositions) {
            $io->error('Could not fetch race results.');

            return Command::FAILURE;
        }

        // 9a. Lap-by-lap positions + GPO calculation from LapSeries
        [$totalLaps, $gpoByNumber] = $this->importLapByLap(
            $raceSession['Path'],
            $driverByNumber,
            $teamByNumber,
            $result,
            $racePositions,
            $raceFinished,
            $io,
        );
        $io->text(sprintf('Created lap-by-lap ResultLaps (%d total laps).', $totalLaps));

        // Update SeasonRace.laps
        if (null !== $seasonRace && $totalLaps > 0) {
            $seasonRace->setLaps($totalLaps);
        }

        // 10. Persist ResultLaps now so we can reference them in performances
        $this->em->flush();

        // 11. Build DriverPerformances via existing CommandBus handler
        $io->section('Driver performances');
        $driverPerformances = [];

        foreach ($racePositions as $num => $racePos) {
            $driver = $driverByNumber[$num] ?? null;
            if (null === $driver) {
                continue;
            }

            $qualPos = (string) ($qualPositions[$num] ?? '20');
            $sprintPos = null !== $sprintPositions ? (string) ($sprintPositions[$num] ?? null) : null;
            $positionGain = $gpoByNumber[$num] ?? 0;

            /** @var DriverPerformanceInterface $dp */
            $dp = $this->commandBus->dispatch(new SaveDriverPerformanceCommand(
                season: $season,
                race: $race,
                driver: $driver,
                result: $result,
                qualificationPosition: $qualPos,
                positionGain: $positionGain,
                position: is_int($racePos) ? $racePos : null,
                sprintPosition: $sprintPos,
            ));

            $this->em->persist($dp);
            $driverPerformances[$num] = $dp;

            $io->text(sprintf(
                '  %s: race P%s / qual P%s / gain %+d',
                $driverList[$num]['Tla'] ?? $num,
                $racePos ?? '?',
                $qualPos,
                $positionGain,
            ));
        }

        // 12. Build TeamPerformances via existing CommandBus handler
        $io->section('Team performances');
        $teamDrivers = $this->groupDriversByTeam($driverList, $driverByNumber, $driverPerformances);

        // Number of drivers on the grid this GP — used as the DNF penalty position.
        // A DNF driver counts as last place for team score purposes only.
        // Dynamic: 22 in 2026, adapts automatically to any season grid size.
        $nbDrivers = \count($driverByNumber);

        /** @var array<string, TeamPerformanceInterface> $allTeamPerformances */
        $allTeamPerformances = [];

        foreach ($teamDrivers as $teamName => $teamData) {
            $team = $teamData['team'];
            $drivers = $teamData['drivers']; // [['num' => string, 'dp' => DriverPerformanceInterface], ...]

            if (\count($drivers) < 2) {
                $io->warning(sprintf('  Team "%s" has fewer than 2 driver performances — skipped.', $teamName));
                continue;
            }

            /** @var TeamPerformanceInterface $tp */
            $tp = $this->commandBus->dispatch(new SaveTeamPerformanceCommand(
                season: $season,
                race: $race,
                team: $team,
                driverPerformance1: $drivers[0]['dp'],
                driverPerformance2: $drivers[1]['dp'],
                result: $result,
            ));

            // Override the score computed by the handler: DNF drivers count as last place ($nbDrivers)
            // instead of their actual classified position.
            // This only affects team ranking/multiplier — the driver's real position is preserved.
            $adjustedScore = 0;
            foreach ($drivers as $entry) {
                $num = $entry['num'];
                $dp = $entry['dp'];
                $qualPos = (int) $dp->getQualificationPosition();
                $isFinisher = $raceFinished[$num] ?? false;
                $raceScorePos = $isFinisher ? ($dp->getPosition() ?? $nbDrivers) : $nbDrivers;
                $adjustedScore += $qualPos + $raceScorePos;
            }
            $tp->setScore($adjustedScore);

            $allTeamPerformances[$teamName] = $tp;
        }

        // Sort by score ASC — score = sum(qualPos + racePos) for both drivers,
        // so lower = better team (positions are smaller numbers when finishing higher)
        uasort($allTeamPerformances, static fn (TeamPerformanceInterface $a, TeamPerformanceInterface $b): int =>
            $a->getScore() <=> $b->getScore()
        );

        // Assign position rank and multiplier (P_1=20→2.0 for best team, P_10=11→1.1, P_DEFAULT=10→1.0)
        $rank = 1;
        foreach ($allTeamPerformances as $teamName => $tp) {
            $multiplierValue = TeamMultiplierEnum::getPointsFromPosition((string) $rank)->value;
            $tp->setPosition($rank)->setMultiplier($multiplierValue);
            $this->em->persist($tp);
            $io->text(sprintf(
                '  Team "%s" — rank %d / multiplier ×%.1f',
                $teamName,
                $rank,
                $multiplierValue / 10,
            ));
            ++$rank;
        }

        $this->em->flush();

        $io->section('Season Game scores');
        $this->commandBus->dispatch(new ComputeSeasonGPScoresCommand($race->getUuid()));
        $io->text('Season Game scores computed.');

        $io->success(sprintf('GP #%d (%s) imported successfully.', $gpNumber, $meetingName));

        return Command::SUCCESS;
    }

    /**
     * Find GP meeting by sequential GP number (skipping pre-season testing).
     */
    private function findGpMeeting(array $meetings, int $gpNumber): ?array
    {
        $gpCounter = 0;
        foreach ($meetings as $meeting) {
            if (null === $this->findSessionByType($meeting['Sessions'], 'race')) {
                continue;
            }
            ++$gpCounter;
            if ($gpCounter === $gpNumber) {
                return $meeting;
            }
        }

        return null;
    }

    /**
     * Find a session within a meeting by logical type using its Path.
     * Types: 'race', 'qualifying', 'sprint', 'sprint_qualifying'.
     */
    private function findSessionByType(array $sessions, string $type): ?array
    {
        foreach ($sessions as $session) {
            $path = rtrim($session['Path'] ?? '', '/');
            $lastSegment = substr($path, strrpos($path, '/') + 1);

            $match = match ($type) {
                'race' => str_ends_with($lastSegment, '_Race'),
                'qualifying' => str_ends_with($lastSegment, '_Qualifying') && !str_contains($lastSegment, 'Sprint'),
                'sprint' => str_ends_with($lastSegment, '_Sprint') && !str_contains($lastSegment, 'Qualifying'),
                'sprint_qualifying' => str_contains($lastSegment, 'Sprint') && str_contains($lastSegment, 'Qualifying'),
                default => false,
            };

            if ($match) {
                return $session;
            }
        }

        return null;
    }

    /**
     * Build driver and team lookup tables keyed by racing number.
     *
     * @param array<string, mixed> $driverList
     *
     * @return array{array<string, DriverInterface>, array<string, TeamInterface>}
     */
    private function buildDriverTeamLookups(array $driverList, mixed $season, SymfonyStyle $io): array
    {
        $allDrivers = $this->driverRepository->getAll() ?? [];
        $driversByFullName = [];
        foreach ($allDrivers as $driver) {
            $driversByFullName[$driver->getFirstName().' '.$driver->getLastName()] = $driver;
        }

        $allTeams = $this->teamRepository->getAll() ?? [];
        $teamsByName = [];
        foreach ($allTeams as $team) {
            $teamsByName[$team->getName()] = $team;
        }

        $driverByNumber = [];
        $teamByNumber = [];

        foreach ($driverList as $num => $info) {
            if (!is_array($info) || !isset($info['FirstName'], $info['LastName'])) {
                continue;
            }

            $fullName = $info['FirstName'].' '.$info['LastName'];
            $driver = $driversByFullName[$fullName] ?? null;
            if (null === $driver) {
                $io->warning(sprintf('Driver "%s" not in DB — run sync-season first.', $fullName));
                continue;
            }
            $driverByNumber[(string) $num] = $driver;

            $team = $teamsByName[$info['TeamName']] ?? null;
            if (null !== $team) {
                $teamByNumber[(string) $num] = $team;
            }
        }

        return [$driverByNumber, $teamByNumber];
    }

    /**
     * Fetch race final classification with positions AND finished status.
     * Status 1088 = Finished; anything else = DNF/DSQ.
     * The finished flag matters for GPO: only finishers get the official final position
     * applied on their last lap (handles post-race time penalties).
     * DNF drivers keep their last known on-track position instead.
     *
     * @return array{array<string, int|null>|null, array<string, bool>}
     *         [positions, finishedFlags] — positions is null on fetch failure
     */
    private function fetchRaceFinalClassification(string $sessionPath, SymfonyStyle $io): array
    {
        $base = sprintf('%s/%s', self::BASE_URL, $sessionPath);

        $data = $this->fetchJson($base.'FinalClassification.json');
        if (null === $data) {
            $data = $this->fetchJson($base.'TimingData.json');
        }

        if (null === $data) {
            $io->warning(sprintf('  Could not fetch race classification from %s', $base));

            return [null, []];
        }

        $lines = $data['Lines'] ?? $data;
        if (!\is_array($lines)) {
            return [null, []];
        }

        $positions = [];
        $finished = [];

        foreach ($lines as $num => $info) {
            if (str_starts_with((string) $num, '_') || !\is_array($info)) {
                continue;
            }
            $rawPos = $info['Position'] ?? $info['Line'] ?? null;
            $positions[(string) $num] = null !== $rawPos ? (int) $rawPos : null;
            // Status 1088 = Finished; DSQ/DNF/DNS have other codes
            $finished[(string) $num] = isset($info['Status']) && 1088 === (int) $info['Status'];
        }

        $io->text(sprintf(
            '  FinalClassification: %d drivers (%d finished, %d DNF/other)',
            \count($positions),
            \count(array_filter($finished)),
            \count(array_filter($finished, static fn (bool $f) => !$f)),
        ));

        return [$positions, $finished];
    }

    /**
     * Fetch final positions from a session path (qualifying / sprint — no status needed).
     * Tries FinalClassification.json first, falls back to TimingData.json.
     *
     * @return array<string, int|null>|null  racing_number → position (null = DNF/no time)
     */
    private function fetchTimingPositions(string $sessionPath, SymfonyStyle $io): ?array
    {
        $base = sprintf('%s/%s', self::BASE_URL, $sessionPath);

        // Try FinalClassification.json first
        $data = $this->fetchJson($base.'FinalClassification.json');
        $source = 'FinalClassification';

        if (null === $data) {
            // Fallback to TimingData.json
            $data = $this->fetchJson($base.'TimingData.json');
            $source = 'TimingData';
        }

        if (null === $data) {
            $io->warning(sprintf('  Could not fetch session data from %s', $base));

            return null;
        }

        $io->text(sprintf('  Fetched from %s.json', $source));

        $lines = $data['Lines'] ?? $data;
        if (!\is_array($lines)) {
            return null;
        }

        $positions = [];
        foreach ($lines as $num => $info) {
            if (str_starts_with((string) $num, '_') || !\is_array($info)) {
                continue;
            }
            $rawPos = $info['Position'] ?? $info['Line'] ?? null;
            $positions[(string) $num] = null !== $rawPos ? (int) $rawPos : null;
        }

        return $positions;
    }

    /**
     * Create ResultLap entries for a given session type (QUALIFICATION or SPRINT).
     *
     * @param array<string, int|null>     $positions
     * @param array<string, DriverInterface> $driverByNumber
     * @param array<string, TeamInterface>   $teamByNumber
     */
    private function createResultLaps(
        array $positions,
        array $driverByNumber,
        array $teamByNumber,
        Result $result,
        TypeResultEnum $type,
        SymfonyStyle $io,
    ): void {
        foreach ($positions as $num => $position) {
            $driver = $driverByNumber[$num] ?? null;
            $team = $teamByNumber[$num] ?? null;

            if (null === $driver) {
                continue;
            }

            $lap = (new ResultLap())
                ->setResult($result)
                ->setNoLap(1)
                ->setPlace(null !== $position ? (string) $position : 'DNF')
                ->setType($type)
                ->setDriver($driver)
                ->setTeam($team)
            ;

            $this->em->persist($lap);
        }
    }

    /**
     * Fetch LapSeries.json, create lap-by-lap NORMAL ResultLaps, and compute GPO per driver.
     *
     * GPO (Gains de Position) algorithm:
     *   - Grid position = LapSeries[driver][0]
     *   - For each lap 1..N: gain = prev_pos - curr_pos; accumulate only if positive
     *   - Last lap of a FINISHER: substitute official FinalClassification position
     *     (handles post-race time penalties that shift the final order)
     *   - Last lap of a DNF: keep last real on-track position, no substitution
     *   - GPO is always >= 0
     *
     * @param array<string, int|null> $officialPositions  driver number → official final position
     * @param array<string, bool>     $finishedFlags      driver number → true if classified Finished
     *
     * @return array{int, array<string, int>}  [totalLaps, gpoByDriverNumber]
     */
    private function importLapByLap(
        string $sessionPath,
        array $driverByNumber,
        array $teamByNumber,
        Result $result,
        array $officialPositions,
        array $finishedFlags,
        SymfonyStyle $io,
    ): array {
        $base = sprintf('%s/%s', self::BASE_URL, $sessionPath);

        $lapSeries = $this->fetchJson($base.'LapSeries.json');
        $lapCount = $this->fetchJson($base.'LapCount.json');

        if (null === $lapSeries || null === $lapCount) {
            $io->warning('  LapSeries.json or LapCount.json unavailable — lap-by-lap skipped.');

            return [0, []];
        }

        $totalLaps = (int) ($lapCount['TotalLaps'] ?? 0);
        if (0 === $totalLaps) {
            return [0, []];
        }

        $gpoByNumber = [];

        foreach ($lapSeries as $num => $entry) {
            $num = (string) $num;
            $driver = $driverByNumber[$num] ?? null;
            $team = $teamByNumber[$num] ?? null;

            if (null === $driver || !\is_array($entry)) {
                continue;
            }

            $allPositions = $entry['LapPosition'] ?? [];
            // Index 0 = grid position; index 1..N = position at end of lap N
            $gridPos = (int) ($allPositions[0] ?? 0);
            $lapPositions = \array_slice($allPositions, 1);

            // Find the last lap index where this driver has a real position
            $lastKnownIndex = -1;
            foreach ($lapPositions as $i => $pos) {
                if ('' !== (string) $pos) {
                    $lastKnownIndex = $i;
                }
            }

            // ── GPO calculation ──────────────────────────────────────────────────
            $gpo = 0;
            $prevPos = $gridPos > 0 ? $gridPos : 20; // fallback if grid pos missing
            $isFinisher = $finishedFlags[$num] ?? false;

            for ($i = 0; $i <= $lastKnownIndex; ++$i) {
                $rawPos = (string) ($lapPositions[$i] ?? '');
                if ('' === $rawPos) {
                    break; // DNF mid-race
                }

                $currPos = (int) $rawPos;

                // For finishers: replace last lap position with official final position
                // (accounts for post-race penalties that change the classification)
                if ($isFinisher && $i === $lastKnownIndex) {
                    $officialPos = $officialPositions[$num] ?? null;
                    if (null !== $officialPos) {
                        $currPos = $officialPos;
                    }
                }

                $gain = $prevPos - $currPos;
                if ($gain > 0) {
                    $gpo += $gain;
                }
                $prevPos = $currPos;
            }

            $gpoByNumber[$num] = $gpo;

            // ── ResultLap entities ───────────────────────────────────────────────
            for ($lapNo = 1; $lapNo <= $totalLaps; ++$lapNo) {
                $rawPos = $lapPositions[$lapNo - 1] ?? '';
                $place = '' !== (string) $rawPos ? (string) $rawPos : 'DNF';

                $lap = (new ResultLap())
                    ->setResult($result)
                    ->setNoLap($lapNo)
                    ->setPlace($place)
                    ->setType(TypeResultEnum::NORMAL)
                    ->setDriver($driver)
                    ->setTeam($team)
                ;

                $this->em->persist($lap);
            }
        }

        return [$totalLaps, $gpoByNumber];
    }

    /**
     * Group driver performances by team for SaveTeamPerformanceCommand.
     * Each driver entry includes the racing number so DNF status can be checked later.
     *
     * @param array<string, mixed>                    $driverList
     * @param array<string, DriverInterface>          $driverByNumber
     * @param array<string, DriverPerformanceInterface> $driverPerformances
     *
     * @return array<string, array{team: TeamInterface, drivers: array<array{num: string, dp: DriverPerformanceInterface}>}>
     */
    private function groupDriversByTeam(
        array $driverList,
        array $driverByNumber,
        array $driverPerformances,
    ): array {
        $allTeams = $this->teamRepository->getAll() ?? [];
        $teamsByName = [];
        foreach ($allTeams as $team) {
            $teamsByName[$team->getName()] = $team;
        }

        $teamDrivers = [];

        foreach ($driverList as $num => $info) {
            if (!is_array($info) || !isset($info['TeamName'])) {
                continue;
            }

            $teamName = $info['TeamName'];
            $dp = $driverPerformances[(string) $num] ?? null;
            $team = $teamsByName[$teamName] ?? null;

            if (null === $dp || null === $team) {
                continue;
            }

            if (!isset($teamDrivers[$teamName])) {
                $teamDrivers[$teamName] = ['team' => $team, 'drivers' => []];
            }

            $teamDrivers[$teamName]['drivers'][] = ['num' => (string) $num, 'dp' => $dp];
        }

        return $teamDrivers;
    }

    /**
     * Fetch JSON from F1 Live Timing, handling UTF-8 BOM.
     *
     * @return array<mixed>|null
     */
    private function fetchJson(string $url): ?array
    {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => 'User-Agent: Mozilla/5.0 (compatible; KOP-F1-Importer/1.0)',
                'timeout' => 20,
            ],
        ]);

        $content = @file_get_contents($url, false, $context);
        if (false === $content) {
            return null;
        }

        $content = ltrim($content, "\xEF\xBB\xBF");

        try {
            return json_decode($content, true, 512, \JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return null;
        }
    }
}
