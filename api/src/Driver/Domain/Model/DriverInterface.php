<?php

namespace App\Driver\Domain\Model;

use App\Performance\Domain\Model\DriverPerformanceInterface;
use App\Shared\Domain\Model\Behaviors\HasColor;
use App\Shared\Domain\Model\Behaviors\HasImage;
use App\Shared\Domain\Model\Behaviors\HasMinValue;
use App\Shared\Domain\Model\Behaviors\HasResults;
use App\Shared\Domain\Model\Behaviors\Idable;
use App\Shared\Domain\Model\Behaviors\Stringifyable;
use App\Shared\Domain\Model\Behaviors\Timestampable;
use App\Shared\Domain\Model\Behaviors\Uuidable;
use App\Team\Domain\Model\TeamInterface;
use Doctrine\Common\Collections\Collection;

interface DriverInterface extends Stringifyable, Idable, Uuidable, Timestampable, HasImage, HasMinValue, HasResults, HasColor
{
    public function getFirstName(): ?string;

    public function getLastName(): ?string;

    public function setFirstName(?string $firstName): static;

    public function setLastName(?string $lastName): static;

    public function getName(): string;

    public function isReplacement(): ?bool;

    public function setIsReplacement(?bool $isReplacement): static;

    public function getReplacementDateStart(): ?\DateTimeImmutable;

    public function setReplacementDateStart(?\DateTimeImmutable $date): static;

    public function getReplacementDateEnd(): ?\DateTimeImmutable;

    public function setReplacementDateEnd(?\DateTimeImmutable $date): ?static;

    public function getTeam(): ?TeamInterface;

    public function setTeam(?TeamInterface $team): static;

    public function setReplacedPermanently(?bool $replacedPermanently): static;

    public function isReplacedPermanently(): bool;

    public function getReplacedBy(): ?self;

    public function setReplacedBy(?self $driver): static;

    public function getPerformances(): ?Collection;

    public function addPerformance(DriverPerformanceInterface $driverPerformance): static;

    public function removePerformance(DriverPerformanceInterface $driverPerformance): void;

    public function getCurrentlyReplacedBy(): ?self;
}
