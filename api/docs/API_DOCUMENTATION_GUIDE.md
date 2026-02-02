# Guide de Documentation API - King of Paddock

Ce guide explique comment documenter les endpoints de l'API King of Paddock en utilisant API Platform et OpenAPI 3.0.

## Table des matières

1. [Principes généraux](#principes-généraux)
2. [Documentation des Resources](#documentation-des-resources)
3. [Documentation des opérations](#documentation-des-opérations)
4. [Documentation des propriétés](#documentation-des-propriétés)
5. [Documentation des erreurs](#documentation-des-erreurs)
6. [Organisation par tags](#organisation-par-tags)
7. [Exemples](#exemples)
8. [Validation](#validation)

---

## Principes généraux

### Langue
- **Descriptions utilisateur** : Français
- **Termes techniques** : Anglais (GET, POST, UUID, etc.)
- **Messages d'erreur** : Français

### Clarté
- Descriptions concises et précises
- Inclure les contraintes (min/max, format, requis)
- Exemples réalistes avec données F1
- UUIDs au format valide

---

## Documentation des Resources

### Imports nécessaires

```php
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\RequestBody;
```

### Structure de base

```php
#[ApiResource(
    shortName: 'Driver',
    description: 'Pilotes disponibles pour les enchères dans un championnat',
    operations: [
        // Opérations ici
    ],
    security: "is_granted('IS_AUTHENTICATED_FULLY')",
)]
class DriverResource
{
    // Propriétés ici
}
```

---

## Documentation des opérations

### GetCollection - Liste de ressources

```php
new GetCollection(
    openapi: new Operation(
        summary: 'Liste des pilotes disponibles',
        description: 'Récupère tous les pilotes disponibles pour les enchères dans un championnat spécifique. Inclut les informations sur l\'écurie, la valeur minimale et les remplaçants.',
        tags: ['Pilotes'],
    ),
),
```

### Get - Ressource unique

```php
new Get(
    openapi: new Operation(
        summary: 'Détails d\'un championnat',
        description: 'Récupère les informations complètes d\'un championnat incluant les joueurs, les courses et le classement.',
        tags: ['Championnats'],
        responses: [
            '200' => [
                'description' => 'Détails du championnat',
            ],
            '404' => [
                'description' => 'Championnat non trouvé',
            ],
        ],
    ),
),
```

### Post - Création/Action

```php
new Post(
    input: CreateChampionshipDto::class,
    openapi: new Operation(
        summary: 'Créer un championnat',
        description: 'Crée un nouveau championnat avec les paramètres spécifiés. L\'utilisateur devient le créateur et le premier joueur du championnat.',
        tags: ['Championnats'],
        requestBody: new RequestBody(
            description: 'Paramètres du championnat à créer',
            required: true,
        ),
        responses: [
            '201' => [
                'description' => 'Championnat créé avec succès',
            ],
            '400' => [
                'description' => 'Données invalides',
                'content' => [
                    'application/ld+json' => [
                        'example' => [
                            '@type' => 'ConstraintViolationList',
                            'violations' => [
                                [
                                    'propertyPath' => 'name',
                                    'message' => 'Le nom doit contenir entre 3 et 30 caractères',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ),
),
```

---

## Documentation des propriétés

### Propriété simple

```php
#[ApiProperty(
    description: 'Nom complet du pilote',
    example: 'Max Verstappen',
)]
public ?string $name = null;
```

### Propriété avec contraintes

```php
#[Assert\Length(min: 3, max: 30)]
#[ApiProperty(
    description: 'Nom du championnat',
    example: 'Championnat F1 2024',
)]
public ?string $name = null;
```

### Identifiant

```php
#[Assert\Uuid]
#[ApiProperty(
    identifier: true,
    description: 'Identifiant unique du pilote',
    example: '550e8400-e29b-41d4-a716-446655440000',
)]
public ?string $uuid = null;
```

### Propriété numérique

```php
#[Assert\PositiveOrZero]
#[ApiProperty(
    description: 'Valeur minimale d\'enchère pour ce pilote en crédits',
    example: 15000000,
)]
public ?int $minValue = null;
```

### Propriété de relation

```php
#[ApiProperty(
    readableLink: true,
    writableLink: false,
    description: 'Écurie du pilote',
)]
public ?TeamResource $team = null;
```

### Propriété de date

```php
#[ApiProperty(
    description: 'Date de fin des enchères pour la manche en cours',
    example: '2024-05-20 18:00:00',
)]
public ?\DateTimeImmutable $currentRoundEndDate = null;
```

---

## Documentation des erreurs

### Erreurs de validation (400)

```php
'400' => [
    'description' => 'Données invalides',
    'content' => [
        'application/ld+json' => [
            'example' => [
                '@type' => 'ConstraintViolationList',
                'violations' => [
                    [
                        'propertyPath' => 'amount',
                        'message' => 'L\'enchère minimum est de 1M crédits',
                    ],
                ],
            ],
        ],
    ],
],
```

### Erreurs métier (409)

```php
'409' => [
    'description' => 'Conflit métier',
    'content' => [
        'application/ld+json' => [
            'example' => [
                '@type' => 'hydra:Error',
                'hydra:description' => 'Solde insuffisant pour cette enchère',
            ],
        ],
    ],
],
```

### Ressource non trouvée (404)

```php
'404' => [
    'description' => 'Pilote non trouvé',
],
```

### Accès refusé (403)

```php
'403' => [
    'description' => 'Vous n\'êtes pas le créateur de ce championnat',
],
```

### Erreur d'état (422)

```php
'422' => [
    'description' => 'Enchère fermée',
    'content' => [
        'application/ld+json' => [
            'example' => [
                '@type' => 'hydra:Error',
                'hydra:description' => 'Cette enchère est terminée',
            ],
        ],
    ],
],
```

---

## Organisation par tags

Les endpoints sont organisés par domaines fonctionnels :

- **Authentification** - Connexion, inscription, mot de passe
- **Pilotes** - Consultation des pilotes F1
- **Écuries** - Consultation des écuries F1
- **Championnats** - Création et gestion des championnats
- **Joueurs** - Profils et équipes des joueurs
- **Enchères** - Système d'enchères pour pilotes/écuries
- **Bonus** - Bonus achetables avec crédits
- **Portefeuille** - Gestion des crédits virtuels
- **Courses** - Calendrier et résultats
- **Classements** - Scores et positions

---

## Exemples

### Données réalistes KOP

#### Pilotes
```php
#[ApiProperty(example: 'Max Verstappen')]      // name
#[ApiProperty(example: 'Red Bull Racing')]     // team
#[ApiProperty(example: 'NED')]                 // nationality
#[ApiProperty(example: 1)]                     // car number
#[ApiProperty(example: 575)]                   // points
#[ApiProperty(example: 32500000)]              // value in credits
#[ApiProperty(example: '#0600EF')]             // color
```

#### Écuries
```php
#[ApiProperty(example: 'Red Bull Racing')]     // name
#[ApiProperty(example: '#1E41FF')]             // color
#[ApiProperty(example: 20000000)]              // minValue
```

#### Courses
```php
#[ApiProperty(example: 'Grand Prix de Monaco')]     // name
#[ApiProperty(example: 'Monaco')]                   // location
#[ApiProperty(example: 'Circuit de Monaco')]        // circuit
#[ApiProperty(example: '2024-05-26')]               // date
```

#### Enchères
```php
#[ApiProperty(example: 25000000)]                   // amount
#[ApiProperty(example: '2024-05-20T18:00:00+02:00')] // closesAt
```

#### Crédits
```php
#[ApiProperty(example: 50000000)]              // credits
#[ApiProperty(example: 4.99)]                  // price in euros
```

---

## Validation

### Générer le fichier OpenAPI

```bash
cd /Users/splyy/Works/kop/api
php bin/console api:openapi:export --output=public/openapi.json
```

### Valider avec Redocly CLI

```bash
php bin/console api:openapi:export | npx @redocly/cli lint -
```

### Visualiser dans Swagger UI

Accédez à : `https://kop.local/api/docs`

---

## Checklist par Resource

### Pour chaque Resource
- [ ] `description` sur `ApiResource`
- [ ] `summary` et `description` sur chaque opération
- [ ] `tags` pour le groupement
- [ ] `example` sur chaque `ApiProperty`
- [ ] Codes de réponse documentés (200, 201, 204, 400, 403, 404, 409, 422)
- [ ] Exemples d'erreurs pour les cas d'échec

### Pour chaque Input DTO
- [ ] `description` sur chaque propriété
- [ ] `example` avec valeur réaliste
- [ ] Messages de validation en français
- [ ] Champs requis marqués

### Global
- [ ] Tags organisés logiquement
- [ ] Schéma de sécurité documenté
- [ ] Serveurs listés (prod, staging, local)
- [ ] Version de l'API maintenue

---

## Fichiers importants

### Configuration
- `/Users/splyy/Works/kop/api/config/packages/api_platform.php` - Configuration API Platform
- `/Users/splyy/Works/kop/api/config/packages/services/shared.php` - Enregistrement du decorator

### Code
- `/Users/splyy/Works/kop/api/src/Shared/Infrastructure/ApiPlatform/OpenApi/OpenApiDecorator.php` - Decorator global
- `/Users/splyy/Works/kop/api/src/{Context}/Infrastructure/ApiPlatform/Resource/*Resource.php` - Resources API

### Documentation générée
- `/Users/splyy/Works/kop/api/public/openapi.json` - Spécification OpenAPI exportée

---

## Bonnes pratiques

1. **Cohérence** : Utilisez le même pilote/équipe dans les exemples liés
2. **Complétude** : Documentez TOUS les cas d'erreur possibles
3. **Clarté** : Expliquez le "pourquoi", pas seulement le "quoi"
4. **Réalisme** : Utilisez des données F1 réelles dans les exemples
5. **Maintenance** : Mettez à jour la documentation lors des changements d'API

---

## Ressources

- [API Platform Documentation](https://api-platform.com/docs/core/openapi/)
- [OpenAPI Specification](https://swagger.io/specification/)
- [Swagger UI](https://swagger.io/tools/swagger-ui/)
