<?php

declare(strict_types=1);

namespace App\Shared\Domain\Model\Behaviors;

use Doctrine\Common\Collections\Collection;

interface HasResults
{
    public function getResults(): ?Collection;
}
