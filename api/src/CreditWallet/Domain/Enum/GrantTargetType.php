<?php

declare(strict_types=1);

namespace App\CreditWallet\Domain\Enum;

enum GrantTargetType: string
{
    case ALL = 'all';
    case PLAYER = 'player';
    case CHAMPIONSHIP = 'championship';

    public static function choices(): array
    {
        return [
            'Tous les joueurs' => self::ALL->value,
            'Un joueur spécifique' => self::PLAYER->value,
            'Joueurs d\'un championnat' => self::CHAMPIONSHIP->value,
        ];
    }
}
