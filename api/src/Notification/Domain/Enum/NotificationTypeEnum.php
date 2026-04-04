<?php

declare(strict_types=1);

namespace App\Notification\Domain\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum NotificationTypeEnum: string implements TranslatableInterface
{
    case SYSTEM_RESULT_PUBLISHED = 'SYSTEM_RESULT_PUBLISHED';
    case SYSTEM_CORRECTION_GENERIC = 'SYSTEM_CORRECTION_GENERIC';
    case SYSTEM_CORRECTION_DETAILED = 'SYSTEM_CORRECTION_DETAILED';
    case SYSTEM_CREDIT = 'SYSTEM_CREDIT';
    case EDITORIAL = 'EDITORIAL';

    public function getLabel(): string
    {
        return match ($this) {
            self::SYSTEM_RESULT_PUBLISHED => 'Résultat GP publié',
            self::SYSTEM_CORRECTION_GENERIC => 'Correction GP (générique)',
            self::SYSTEM_CORRECTION_DETAILED => 'Correction GP (détaillée)',
            self::SYSTEM_CREDIT => 'Crédit reçu',
            self::EDITORIAL => 'Éditorial',
        };
    }

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        return $this->getLabel();
    }

    public static function choices(): array
    {
        $choices = [];
        foreach (self::cases() as $case) {
            $choices[$case->getLabel()] = $case->value;
        }

        return $choices;
    }
}
