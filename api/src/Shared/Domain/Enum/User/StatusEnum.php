<?php

declare(strict_types=1);

namespace App\Shared\Domain\Enum\User;

use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Contracts\Translation\TranslatorInterface;

use function Symfony\Component\Translation\t;

enum StatusEnum: string
{
    case PASSWORD_TO_CREATE = 'PASSWORD_TO_CREATE';
    case EMAIL_TO_VALIDATE = 'EMAIL_TO_VALIDATE';
    case WAITING_ADMIN_CONFIRMATION = 'WAITING_ADMIN_CONFIRMATION';
    case CREATED = 'CREATED';
    case DELETED = 'DELETED';

    public function getLabel(): TranslatableMessage
    {
        return t('user.status.'.$this->name, domain: 'user');
    }

    public function getColorCssClass(): string
    {
        return match ($this) {
            self::PASSWORD_TO_CREATE, self::EMAIL_TO_VALIDATE => 'warning',
            self::WAITING_ADMIN_CONFIRMATION => 'danger',
            self::DELETED => 'dark',
            self::CREATED => 'success',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function visitorFilters(TranslatorInterface $translator): array
    {
        $choices = [];

        foreach ([self::EMAIL_TO_VALIDATE, self::CREATED] as $status) {
            $choices[$status->getLabel()->trans($translator)] = $status->value;
        }

        return $choices;
    }

    /**
     * @return array<StatusEnum>
     */
    public static function isActive(): array
    {
        return [self::PASSWORD_TO_CREATE, self::EMAIL_TO_VALIDATE, self::WAITING_ADMIN_CONFIRMATION, self::CREATED];
    }
}
