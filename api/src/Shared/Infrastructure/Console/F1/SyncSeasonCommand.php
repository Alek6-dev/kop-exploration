<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Console\F1;

use App\Driver\Domain\Repository\DriverRepositoryInterface;
use App\Driver\Infrastructure\Doctrine\Entity\Driver;
use App\Race\Domain\Enum\CountryEnum;
use App\Race\Domain\Repository\RaceRepositoryInterface;
use App\Race\Infrastructure\Doctrine\Entity\Race;
use App\Season\Domain\Repository\SeasonRaceRepositoryInterface;
use App\Season\Domain\Repository\SeasonRepositoryInterface;
use App\Season\Domain\Repository\SeasonTeamRepositoryInterface;
use App\Season\Infrastructure\Doctrine\Entity\Season;
use App\Season\Infrastructure\Doctrine\Entity\SeasonRace;
use App\Season\Infrastructure\Doctrine\Entity\SeasonTeam;
use App\Team\Domain\Repository\TeamRepositoryInterface;
use App\Team\Infrastructure\Doctrine\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'kop:f1:sync-season',
    description: 'Sync F1 season calendar, teams and drivers from Ergast mirror + F1 Live Timing.',
)]
final class SyncSeasonCommand extends Command
{
    /**
     * Ergast mirror — full season calendar with all session dates.
     * https://api.jolpi.ca/ergast is a maintained community mirror of the deprecated Ergast API.
     */
    private const string ERGAST_URL = 'https://api.jolpi.ca/ergast/f1/%d/races.json?limit=500';

    /**
     * F1 Live Timing — used only to fetch DriverList (teams + drivers) when available.
     * Only publishes meetings a few days before each GP, so the calendar CANNOT come from here.
     */
    private const string F1LT_BASE_URL = 'https://livetiming.formula1.com/static';

    /**
     * Ergast country name → CountryEnum alpha-2 code.
     */
    private const array COUNTRY_MAP = [
        'Australia'   => 'au',
        'China'       => 'cn',
        'Japan'       => 'jp',
        'Bahrain'     => 'bh',
        'Saudi Arabia' => 'sa',
        'USA'         => 'us',
        'Canada'      => 'ca',
        'Spain'       => 'es',
        'Monaco'      => 'mc',
        'Azerbaijan'  => 'az',
        'UK'          => 'gb',
        'Hungary'     => 'hu',
        'Belgium'     => 'be',
        'Netherlands' => 'nl',
        'Italy'       => 'it',
        'Singapore'   => 'sg',
        'Mexico'      => 'mx',
        'Brazil'      => 'br',
        'UAE'         => 'ae',
        'Qatar'       => 'qa',
        'Latvia'      => 'lv',
        'Austria'     => 'at',
        'Switzerland' => 'ch',
    ];

    public function __construct(
        private readonly SeasonRepositoryInterface $seasonRepository,
        private readonly SeasonRaceRepositoryInterface $seasonRaceRepository,
        private readonly SeasonTeamRepositoryInterface $seasonTeamRepository,
        private readonly RaceRepositoryInterface $raceRepository,
        private readonly TeamRepositoryInterface $teamRepository,
        private readonly DriverRepositoryInterface $driverRepository,
        private readonly EntityManagerInterface $em,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('year', InputArgument::REQUIRED, 'F1 season year (e.g. 2026)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $year = (int) $input->getArgument('year');

        $io->title(sprintf('Syncing F1 %d season', $year));

        // ── 1. CALENDAR from Ergast mirror ────────────────────────────────────────
        $ergastData = $this->fetchJson(sprintf(self::ERGAST_URL, $year));
        $ergastRaces = $ergastData['MRData']['RaceTable']['Races'] ?? null;

        if (empty($ergastRaces)) {
            $io->error(sprintf('Could not fetch calendar from Ergast mirror (api.jolpi.ca). URL: %s', sprintf(self::ERGAST_URL, $year)));

            return Command::FAILURE;
        }

        $io->text(sprintf('Ergast mirror: %d races found for %d', \count($ergastRaces), $year));
        $io->note('Lap counts are set to 0 — fill them in manually via the admin before starting each championship.');

        // ── 2. DRIVER LIST PATHS from F1 Live Timing (optional) ───────────────────
        // Build a map: normalised race name → Race session path (e.g. "Australian Grand Prix" → "2026/..._Race/")
        $f1ltPaths = $this->buildF1LtPathMap($year, $io);

        // ── 3. SEASON ──────────────────────────────────────────────────────────────
        $season = $this->seasonRepository->findOneBy(['name' => (string) $year]);
        if (null === $season) {
            $season = (new Season())->setName((string) $year)->setIsActive(false);
            $this->em->persist($season);
            $this->em->flush();
            $io->text(sprintf('Created season "%d"', $year));
        } else {
            $io->text(sprintf('Found existing season "%d"', $year));
        }

        // ── 4. SYNC EACH RACE ─────────────────────────────────────────────────────
        $driversByFullName = [];
        foreach ($this->driverRepository->getAll() ?? [] as $driver) {
            $driversByFullName[$driver->getFirstName().' '.$driver->getLastName()] = $driver;
        }

        $teamsByName = [];
        foreach ($this->teamRepository->getAll() ?? [] as $team) {
            $teamsByName[$team->getName()] = $team;
        }

        foreach ($ergastRaces as $ergastRace) {
            $this->syncRace($ergastRace, $season, $f1ltPaths, $teamsByName, $driversByFullName, $io);
        }

        $this->em->flush();
        $io->success(sprintf('F1 %d season synced — %d races.', $year, \count($ergastRaces)));

        return Command::SUCCESS;
    }

    /**
     * Build a map of normalised race name → F1 Live Timing Race session path.
     * F1 LT only publishes meetings close to the GP date, so this map will be partial.
     *
     * @return array<string, string> e.g. ["Australian Grand Prix" => "2026/.../Race/"]
     */
    private function buildF1LtPathMap(int $year, SymfonyStyle $io): array
    {
        $index = $this->fetchJson(sprintf('%s/%d/Index.json', self::F1LT_BASE_URL, $year));
        if (null === $index) {
            $io->warning('Could not fetch F1 Live Timing Index.json — teams/drivers will be skipped for all races.');

            return [];
        }

        $map = [];
        foreach ($index['Meetings'] ?? [] as $meeting) {
            $name = $meeting['Name'] ?? '';
            $sessions = $meeting['Sessions'] ?? [];

            // Skip testing sessions
            if (str_contains(strtolower($name), 'testing')) {
                continue;
            }

            $racePath = $this->findRaceSessionPath($sessions);
            if (null !== $racePath) {
                $map[$name] = $racePath;
            }
        }

        $io->text(sprintf('F1 Live Timing: %d meetings with DriverList available', \count($map)));

        return $map;
    }

    /**
     * Find the Race session path in an F1 LT sessions array.
     * For sprint weekends, there are two sessions with Type "Race" — we pick the main race
     * (path ends with _Race, not _Sprint).
     */
    private function findRaceSessionPath(array $sessions): ?string
    {
        $raceSessions = array_filter(
            $sessions,
            static fn (array $s) => 'race' === strtolower($s['Type'] ?? '') && isset($s['Path']),
        );

        foreach ($raceSessions as $session) {
            $path = $session['Path'];
            // Sprint weekend: skip the Sprint session (path contains _Sprint but not _Race at the end)
            $lastSegment = substr($path, strrpos(rtrim($path, '/'), '/') + 1);
            if (str_ends_with($lastSegment, '_Race')) {
                return $path;
            }
        }

        // Non-sprint weekend: only one Race session
        $first = reset($raceSessions);

        return $first ? $first['Path'] : null;
    }

    /**
     * @param array<string, string> $f1ltPaths Map of race name → F1 LT Race session path
     * @param array<string, Team>   $teamsByName
     * @param array<string, Driver> $driversByFullName
     */
    private function syncRace(
        array $ergastRace,
        Season $season,
        array $f1ltPaths,
        array &$teamsByName,
        array &$driversByFullName,
        SymfonyStyle $io,
    ): void {
        $raceName = $ergastRace['raceName'];
        $round = (int) $ergastRace['round'];
        $country = $ergastRace['Circuit']['Location']['country'] ?? null;

        $io->section(sprintf('R%02d — %s', $round, $raceName));

        // ── Country ───────────────────────────────────────────────────────────────
        $countryEnum = null;
        if (null !== $country && isset(self::COUNTRY_MAP[$country])) {
            try {
                $countryEnum = CountryEnum::from(self::COUNTRY_MAP[$country]);
            } catch (\ValueError) {
                $io->warning(sprintf('  Unknown country: "%s"', $country));
            }
        } elseif (null !== $country) {
            $io->warning(sprintf('  Country not in map: "%s"', $country));
        }

        // ── Race entity ───────────────────────────────────────────────────────────
        $isNewRace = false;
        $race = $this->raceRepository->findOneBy(['name' => $raceName]);
        if (null === $race) {
            $race = (new Race())->setName($raceName);
            if (null !== $countryEnum) {
                $race->setCountry($countryEnum);
            }
            $this->em->persist($race);
            $this->em->flush();
            $isNewRace = true;
            $io->text(sprintf('  + Race "%s"', $raceName));
        } else {
            $io->text(sprintf('  ~ Race "%s" (exists)', $raceName));
        }

        // ── Dates from Ergast (already in UTC) ────────────────────────────────────
        $raceDate = $this->ergastToUtcDate($ergastRace['date'], $ergastRace['time'] ?? '00:00:00Z');
        $qualDate = $this->ergastToUtcDate(
            $ergastRace['Qualifying']['date'],
            $ergastRace['Qualifying']['time'] ?? '00:00:00Z',
        );

        $sprintDate = null;
        if (isset($ergastRace['Sprint'])) {
            $sprintDate = $this->ergastToUtcDate(
                $ergastRace['Sprint']['date'],
                $ergastRace['Sprint']['time'] ?? '00:00:00Z',
            );
        }

        $limitStrategyDate = $sprintDate ?? $qualDate;

        // ── SeasonRace ────────────────────────────────────────────────────────────
        $seasonRace = null;
        if (!$isNewRace) {
            $seasonRace = $this->seasonRaceRepository
                ->withSeason($season)
                ->withRace($race)
                ->first();
        }

        if (null === $seasonRace) {
            $seasonRace = new SeasonRace();
            $this->em->persist($seasonRace);
            $io->text('  + SeasonRace');
        } else {
            $io->text('  ~ SeasonRace (updated dates)');
        }

        $seasonRace
            ->setSeason($season)
            ->setRace($race)
            ->setDate($raceDate)
            ->setQualificationDate($qualDate)
            ->setSprintDate($sprintDate)
            ->setLimitStrategyDate($limitStrategyDate)
            ->setLaps(0)
        ;

        // ── Teams & Drivers from F1 Live Timing ───────────────────────────────────
        $racePath = $f1ltPaths[$raceName] ?? null;
        if (null === $racePath) {
            $io->text('  (no F1 LT path yet — teams/drivers will sync on next run)');

            return;
        }

        $driverList = $this->fetchJson(sprintf('%s/%sDriverList.json', self::F1LT_BASE_URL, $racePath));
        if (null === $driverList) {
            $io->warning(sprintf('  Could not fetch DriverList for R%02d', $round));

            return;
        }

        $seasonTeamsCreated = [];

        foreach ($driverList as $num => $info) {
            if (!is_array($info) || !isset($info['FirstName'], $info['LastName'], $info['TeamName'])) {
                continue;
            }

            $teamName = $info['TeamName'];
            $teamColor = '#'.$info['TeamColour'];

            // Team
            $isNewTeam = false;
            if (!isset($teamsByName[$teamName])) {
                $team = (new Team())->setName($teamName)->setColor($teamColor)->setImage('')->setMinValue(0);
                $this->em->persist($team);
                $this->em->flush();
                $teamsByName[$teamName] = $team;
                $isNewTeam = true;
                $io->text(sprintf('  + Team "%s"', $teamName));
            } else {
                $team = $teamsByName[$teamName];
                $team->setColor($teamColor);
            }

            // SeasonTeam
            if (!isset($seasonTeamsCreated[$teamName])) {
                $existing = null;
                if (!$isNewTeam) {
                    $existing = $this->seasonTeamRepository->findOneBy([
                        'season' => $season,
                        'team'   => $team,
                    ]);
                }
                if (null === $existing) {
                    $st = (new SeasonTeam())->setSeason($season)->setTeam($team);
                    $this->em->persist($st);
                    $io->text(sprintf('  + SeasonTeam "%s"', $teamName));
                }
                $seasonTeamsCreated[$teamName] = true;
            }

            // Driver
            $firstName = $info['FirstName'];
            $lastName = $info['LastName'];
            $fullName = $firstName.' '.$lastName;

            if (!isset($driversByFullName[$fullName])) {
                $driver = (new Driver())
                    ->setFirstName($firstName)
                    ->setLastName($lastName)
                    ->setTeam($team)
                    ->setImage('')
                    ->setMinValue(0)
                ;
                $this->em->persist($driver);
                $driversByFullName[$fullName] = $driver;
                $io->text(sprintf('  + Driver "%s"', $fullName));
            } else {
                $driversByFullName[$fullName]->setTeam($team);
            }
        }
    }

    /**
     * Parse Ergast date (YYYY-MM-DD) + time (HH:MM:SSZ) — already in UTC.
     */
    private function ergastToUtcDate(string $date, string $time): \DateTimeImmutable
    {
        return new \DateTimeImmutable(
            $date.'T'.ltrim($time, 'Z'),
            new \DateTimeZone('UTC'),
        );
    }

    /**
     * Fetch JSON, handling UTF-8 BOM (emitted by F1 Live Timing).
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
