<?php

namespace App\Season\Domain\Model;

interface JoinSeasonInterface
{
    public function getSeason(): ?SeasonInterface;

    public function setSeason(?SeasonInterface $season): static;
}
