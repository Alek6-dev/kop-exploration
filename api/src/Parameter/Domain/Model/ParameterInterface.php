<?php

declare(strict_types=1);

namespace App\Parameter\Domain\Model;

use App\Parameter\Domain\Enum\TypeEnum;
use App\Shared\Domain\Model\Behaviors\Idable;
use App\Shared\Domain\Model\Behaviors\Timestampable;
use App\Shared\Domain\Model\Behaviors\Uuidable;

interface ParameterInterface extends Idable, Uuidable, Timestampable
{
    public function getLabel(): ?string;

    public function setLabel(?string $label): static;

    public function getCode(): ?string;

    public function setCode(?string $code): static;

    public function getValue(): string|int|bool|null;

    public function setValue(string|int|bool|null $value): static;

    public function getType(): ?TypeEnum;

    public function setType(TypeEnum $type): static;
}
