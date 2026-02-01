<?php

declare(strict_types=1);

namespace App\Shared\Domain\Enum\User;

use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Contracts\Translation\TranslatorInterface;

use function Symfony\Component\Translation\t;

enum RoleEnum: string
{
    case ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    public function getLabel(): TranslatableMessage
    {
        return t($this->name, domain: 'security');
    }

    /**
     * @return array<string, string>
     */
    public static function adminFilters(TranslatorInterface $translator): array
    {
        $choices = [];

        foreach (self::cases() as $status) {
            $choices[$status->value] = $status->getLabel()->trans($translator);
        }

        return $choices;
    }
}
