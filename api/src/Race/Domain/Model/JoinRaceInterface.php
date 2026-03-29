<?php

namespace App\Race\Domain\Model;

interface JoinRaceInterface
{
    public function getRace(): ?RaceInterface;

    public function setRace(RaceInterface $race): static;
}
