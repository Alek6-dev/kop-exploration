<?php

namespace App\Championship\Domain\Model;

interface JoinChampionshipInterface
{
    public function getChampionship(): ?ChampionshipInterface;

    public function setChampionship(?ChampionshipInterface $championship): static;
}
