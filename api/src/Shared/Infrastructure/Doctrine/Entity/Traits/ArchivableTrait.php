<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait ArchivableTrait
{
    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    protected bool $isArchived = false;

    public function isArchived(): bool
    {
        return $this->isArchived;
    }

    public function setIsArchived(bool $isArchived): void
    {
        $this->isArchived = $isArchived;
    }
}
