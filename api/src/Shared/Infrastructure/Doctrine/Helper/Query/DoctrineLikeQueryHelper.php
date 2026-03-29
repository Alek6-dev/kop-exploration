<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine\Helper\Query;

use function Symfony\Component\String\u;

final class DoctrineLikeQueryHelper
{
    public const ADD_WILDCARD_NONE = 0;
    public const ADD_WILDCARD_START = 1;
    public const ADD_WILDCARD_END = 2;
    public const ADD_WILDCARD_BOTH = 3;

    public const WILDCARD_PERCENT = '%';
    public const WILDCARD_UNDERSCORE = '_';

    /**
     * Escape les wildcards utilisés par l'opérateur "LIKE" de SQL devant être renseigné en tant que paramètre.
     * Permets également d'ajouter le wildcard souhaité au début, à la fin ou aux deux positions du paramètre.
     *
     * Récapitulatif du fonctionnement des wildcards :
     *  - "%" représente aucun, un seul ou plusieurs caractères.
     *  - "_" représente un seul et unique caractère.
     *
     * Exemple dans un champ de recherche : ne pas escape les paramètres des requêtes LIKES permettrait aux valeurs "Ai%ce" et "Air Fr_nce" de retourner le résultat "Air France".
     */
    public static function getSafeParameter(mixed $parameter, int $addWildcard = self::ADD_WILDCARD_BOTH, string $wildcardType = self::WILDCARD_PERCENT): string
    {
        $escapedParameter = u(addcslashes((string) $parameter, self::WILDCARD_PERCENT.self::WILDCARD_UNDERSCORE));

        return (string) match ($addWildcard) {
            self::ADD_WILDCARD_START => $escapedParameter->ensureStart($wildcardType),
            self::ADD_WILDCARD_END => $escapedParameter->ensureEnd($wildcardType),
            self::ADD_WILDCARD_BOTH => $escapedParameter->ensureStart($wildcardType)->ensureEnd($wildcardType),
            default => $escapedParameter,
        };
    }
}
