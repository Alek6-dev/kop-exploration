<?php

declare(strict_types=1);

namespace App\Shared\Domain\Model\Behaviors;

interface SeoMetable
{
    public function getMetaTitle(): ?string;

    public function setMetaTitle(?string $metaTitle): void;

    public function getMetaDescription(): ?string;

    public function setMetaDescription(?string $metaDescription): void;

    public function isEmptyMetaDescription(): bool;
}
