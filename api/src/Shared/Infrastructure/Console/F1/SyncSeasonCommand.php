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
    description: 'Sync F1 season calendar, teams and drivers from F1 Live Timing.',
)]
final class SyncSeasonCommand extends Command
{
    private const string BASE_URL = 'https://livetiming.formula1.com/static';

    /**
     * ISO 3166-1 alpha-3 (F1 API) → alpha-2 lowercase (CountryEnum).
     */
    private const array COUNTRY_MAP = [
        'AUS' => 'au', 'CHN' => 'cn', 'JPN' => 'jp', 'BHR' => 'bh',
        'SAU' => 'sa', 'USA' => 'us', 'CAN' => 'ca', 'ESP' => 'es',
        'MON' => 'mc', 'AZE' => 'az', 'GBR' => 'gb', 'HUN' => 'hu',
        'BEL' => 'be', 'NLD' => 'nl', 'ITA' => 'it', 'SGP' => 'sg',
        'MEX' => 'mx', 'BRA' => 'br', 'UAE' => 'ae', 'QAT' => 'qa',
        'LVA' => 'lv', 'AUT' => 'at', 'SUI' => 'ch',
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

        $index = $this->fetchJson(sprintf('%s/%d/Index.json', self::BASE_URL, $year));
        if (null === $index) {
            $io->error('Could not fetch Index.json from F1 Live Timing.');

            return Command::FAILURE;
        }

        // Find or create Season
        $season = $this->seasonRepository->findOneBy(['name' => (string) $year]);
        if (null === $season) {
            $season = (new Season())->setName((string) $year)->setIsActive(false);
            $this->em->persist($season);
            // Flush now so Season has an ID before using it in sub-queries
            $this->em->flush();
            $io->text(sprintf('Created season "%d"', $year));
        } else {
            $io->text(sprintf('Found existing season "%d"', $year));
        }

        // Filter meetings that have a real Race session
        $gpMeetings = array_values(array_filter(
            $index['Meetings'] ?? [],
            static fn (array $m) => null !== self::findSessionByType($m['Sessions'], 'race'),
        ));

        $io->text(sprintf('Found %d GP meetings', \count($gpMeetings)));

        // Pre-load all drivers and teams for O(1) lookup
        $driversByFullName = [];
        foreach ($this->driverRepository->getAll() ?? [] as $driver) {
            $driversByFullName[$driver->getFirstName().' '.$driver->getLastName()] = $driver;
        }

        $teamsByName = [];
        foreach ($this->teamRepository->getAll() ?? [] as $team) {
            $teamsByName[$team->getName()] = $team;
        }

        foreach ($gpMeetings as $meeting) {
            $this->syncMeeting($meeting, $season, $teamsByName, $driversByFullName, $io);
        }

        $this->em->flush();
        $io->success(sprintf('F1 %d season synced (%d GPs).', $year, \count($gpMeetings)));

        return Command::SUCCESS;
    }

    /**
     * @param array<string, Team>  $teamsByName
     * @param array<string, Driver> $driversByFullName
     */
    private function syncMeeting(
        array $meeting,
        Season $season,
        array &$teamsByName,
        array &$driversByFullName,
        SymfonyStyle $io,
    ): void {
        $sessions = $meeting['Sessions'];
        $meetingName = $meeting['Name'];
        $gpNumber = $meeting['Number'];
        $countryCode3 = $meeting['Country']['Code'] ?? null;

        $raceSession = self::findSessionByType($sessions, 'race');
        $qualifyingSession = self::findSessionByType($sessions, 'qualifying');
        $sprintSession = self::findSessionByType($sessions, 'sprint');

        if (null === $raceSession || null === $qualifyingSession) {
            $io->warning(sprintf('GP #%d %s: missing Race or Qualifying session — skipped.', $gpNumber, $meetingName));

            return;
        }

        $io->section(sprintf('GP #%d — %s', $gpNumber, $meetingName));

        // Map country code
        $countryEnum = null;
        if (null !== $countryCode3 && isset(self::COUNTRY_MAP[$countryCode3])) {
            try {
                $countryEnum = CountryEnum::from(self::COUNTRY_MAP[$countryCode3]);
            } catch (\ValueError) {
                $io->warning(sprintf('  Unknown country code: %s', $countryCode3));
            }
        }

        // Find or create Race
        $isNewRace = false;
        $race = $this->raceRepository->findOneBy(['name' => $meetingName]);
        if (null === $race) {
            $race = (new Race())->setName($meetingName);
            if (null !== $countryEnum) {
                $race->setCountry($countryEnum);
            }
            $this->em->persist($race);
            // Flush to get an ID before using race in SeasonRace query
            $this->em->flush();
            $isNewRace = true;
            $io->text(sprintf('  + Race "%s"', $meetingName));
        } else {
            $io->text(sprintf('  ~ Race "%s" (exists)', $meetingName));
        }

        // Compute dates (convert local time → UTC)
        $raceDate = $this->sessionToUtcDate($raceSession);
        $qualDate = $this->sessionToUtcDate($qualifyingSession);
        $sprintDate = null !== $sprintSession ? $this->sessionToUtcDate($sprintSession) : null;
        $limitStrategyDate = $sprintDate ?? $qualDate;

        // Find or create SeasonRace (new race can't have an existing SeasonRace)
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
            ->setLaps(0)  // updated after import-race
        ;

        // Fetch DriverList from Race session to get teams & drivers
        $driverList = $this->fetchJson(sprintf('%s/%sDriverList.json', self::BASE_URL, $raceSession['Path']));
        if (null === $driverList) {
            $io->warning(sprintf('  Could not fetch DriverList for GP #%d — teams/drivers skipped.', $gpNumber));

            return;
        }

        $seasonTeamsCreated = [];

        foreach ($driverList as $num => $info) {
            if (!is_array($info) || !isset($info['FirstName'], $info['LastName'], $info['TeamName'])) {
                continue;
            }

            $teamName = $info['TeamName'];
            $teamColor = '#'.$info['TeamColour'];

            // Find or create Team
            $isNewTeam = false;
            if (!isset($teamsByName[$teamName])) {
                $team = (new Team())->setName($teamName)->setColor($teamColor)->setImage('')->setMinValue(0);
                $this->em->persist($team);
                // Flush to get an ID before using team in SeasonTeam query
                $this->em->flush();
                $teamsByName[$teamName] = $team;
                $isNewTeam = true;
                $io->text(sprintf('  + Team "%s"', $teamName));
            } else {
                $team = $teamsByName[$teamName];
                $team->setColor($teamColor);
            }

            // Find or create SeasonTeam (once per team per season)
            // New teams can't have an existing SeasonTeam — skip the query
            if (!isset($seasonTeamsCreated[$teamName])) {
                $existing = null;
                if (!$isNewTeam) {
                    $existing = $this->seasonTeamRepository->findOneBy([
                        'season' => $season,
                        'team' => $team,
                    ]);
                }
                if (null === $existing) {
                    $st = (new SeasonTeam())->setSeason($season)->setTeam($team);
                    $this->em->persist($st);
                    $io->text(sprintf('  + SeasonTeam "%s"', $teamName));
                }
                $seasonTeamsCreated[$teamName] = true;
            }

            // Find or create Driver
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
                // Update team assignment (transfers between seasons)
                $driversByFullName[$fullName]->setTeam($team);
            }
        }
    }

    /**
     * Find a session by logical type using the Path field.
     * Types: 'race', 'qualifying', 'sprint', 'sprint_qualifying'.
     */
    private static function findSessionByType(array $sessions, string $type): ?array
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
     * Parse F1 session StartDate (local time) + GmtOffset → UTC DateTimeImmutable.
     */
    private function sessionToUtcDate(array $session): \DateTimeImmutable
    {
        $local = new \DateTimeImmutable($session['StartDate'], new \DateTimeZone('UTC'));
        [$h, $m] = explode(':', $session['GmtOffset'] ?? '00:00:00');
        $offsetSeconds = ((int) $h * 3600) + ((int) $m * 60);

        return $local->modify(sprintf('-%d seconds', $offsetSeconds));
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

        // Strip UTF-8 BOM (EF BB BF) emitted by F1 Live Timing
        $content = ltrim($content, "\xEF\xBB\xBF");

        try {
            return json_decode($content, true, 512, \JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return null;
        }
    }
}
