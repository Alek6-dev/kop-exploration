<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait SluggableTrait
{
    use LabelableTrait;

    #[Gedmo\Slug(fields: ['label'], updatable: false, separator: '-')]
    #[ORM\Column(type: Types::STRING, length: 191, unique: true)]
    protected ?string $slug = null;

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }
}
