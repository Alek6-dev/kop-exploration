<?php

declare(strict_types=1);

namespace App\SeasonGame\Domain\Enum;

enum SeasonBonusTypeEnum: string
{
    case PARC_FERME = 'parc_ferme';
    case ASSURANCE = 'assurance';

    public function label(): string
    {
        return match ($this) {
            self::PARC_FERME => 'Parc Fermé',
            self::ASSURANCE => 'Assurance',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::PARC_FERME => 'Modifier sa stratégie après la date limite du week-end',
            self::ASSURANCE => 'Un pilote qui abandonne marque les points de course de sa position au moment de l\'abandon',
        };
    }

    public function price(): int
    {
        return match ($this) {
            self::PARC_FERME => 2,
            self::ASSURANCE => 10,
        };
    }
}
