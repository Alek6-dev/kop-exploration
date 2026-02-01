<?php

namespace App\Result\Domain\Model;

use App\Driver\Domain\Model\DriverInterface;
use App\Result\Domain\Enum\TypeResultEnum;
use App\Shared\Domain\Model\Behaviors\Idable;
use App\Shared\Domain\Model\Behaviors\Stringifyable;
use App\Shared\Domain\Model\Behaviors\Timestampable;
use App\Shared\Domain\Model\Behaviors\Uuidable;
use App\Team\Domain\Model\TeamInterface;

interface ResultLapInterface extends Stringifyable, Idable, Uuidable, Timestampable
{
    public function getResult(): ?ResultInterface;

    public function setResult(?ResultInterface $result): static;

    public function getType(): ?TypeResultEnum;

    public function setType(?TypeResultEnum $type): static;

    public function getDriver(): ?DriverInterface;

    public function setDriver(?DriverInterface $driver): static;

    public function getTeam(): ?TeamInterface;

    public function setTeam(?TeamInterface $team): static;

    public function getNoLap(): ?int;

    public function setNoLap(?int $noLap): static;

    public function getPlace(): ?string;

    public function setPlace(?string $place): static;
}
