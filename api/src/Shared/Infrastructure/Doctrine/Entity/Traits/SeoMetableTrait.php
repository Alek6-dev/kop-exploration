<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait SeoMetableTrait
{
    #[ORM\Column(type: Types::STRING, length: 191, nullable: true)]
    protected ?string $metaTitle = null;

    #[ORM\Column(type: Types::STRING, length: 191, nullable: true)]
    protected ?string $metaDescription;

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    public function setMetaTitle(?string $metaTitle): void
    {
        $this->metaTitle = $metaTitle;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(?string $metaDescription): void
    {
        $this->metaDescription = $metaDescription;
    }

    public function isEmptyMetaDescription(): bool
    {
        return empty($this->metaDescription);
    }
}
