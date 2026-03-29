<?php

namespace App\Team\Domain\Model;

use App\Driver\Domain\Model\DriverInterface;
use App\Performance\Domain\Model\TeamPerformanceInterface;
use App\Shared\Domain\Model\Behaviors\HasColor;
use App\Shared\Domain\Model\Behaviors\HasImage;
use App\Shared\Domain\Model\Behaviors\HasMinValue;
use App\Shared\Domain\Model\Behaviors\HasResults;
use App\Shared\Domain\Model\Behaviors\Idable;
use App\Shared\Domain\Model\Behaviors\Stringifyable;
use App\Shared\Domain\Model\Behaviors\Timestampable;
use App\Shared\Domain\Model\Behaviors\Uuidable;
use Doctrine\Common\Collections\Collection;

interface TeamInterface extends Stringifyable, Idable, Uuidable, Timestampable, HasImage, HasMinValue, HasResults, HasColor
{
    public function getName(): ?string;

    public function setName(?string $name): static;

    public function getDrivers(): ?Collection;

    public function addDriver(DriverInterface $driver): static;

    public function removeDriver(DriverInterface $driver): void;

    public function getSeasonTeams(): ?Collection;

    public function getPerformances(): ?Collection;

    public function addPerformance(TeamPerformanceInterface $teamPerformance): static;

    public function removePerformance(TeamPerformanceInterface $teamPerformance): void;
}
