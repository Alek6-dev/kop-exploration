<?php

namespace App\Shared\Infrastructure\Doctrine\Entity\Traits;

use Doctrine\Common\Collections\Collection;

trait HasResultTrait
{
    public function getResults(): ?Collection
    {
        return $this->results;
    }
}
