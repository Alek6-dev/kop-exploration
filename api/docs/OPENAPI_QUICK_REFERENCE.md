# OpenAPI Quick Reference - King of Paddock

Guide rapide pour documenter les endpoints API Platform.

---

## Template de Base

```php
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\RequestBody;

#[ApiResource(
    shortName: 'NomCourt',
    description: 'Description de la resource en français',
    operations: [/* ... */],
    security: "is_granted('IS_AUTHENTICATED_FULLY')",
)]
class MaResource { /* ... */ }
```

---

## Opérations Courantes

### GET Collection

```php
new GetCollection(
    openapi: new Operation(
        summary: 'Titre court',
        description: 'Description détaillée de ce que fait cet endpoint.',
        tags: ['NomDuTag'],
    ),
),
```

### GET Item

```php
new Get(
    openapi: new Operation(
        summary: 'Obtenir un élément',
        description: 'Description détaillée.',
        tags: ['NomDuTag'],
        responses: [
            '200' => ['description' => 'Succès'],
            '404' => ['description' => 'Non trouvé'],
        ],
    ),
),
```

### POST

```php
new Post(
    input: MonDto::class,
    openapi: new Operation(
        summary: 'Créer un élément',
        description: 'Description détaillée.',
        tags: ['NomDuTag'],
        requestBody: new RequestBody(
            description: 'Données à envoyer',
            required: true,
        ),
        responses: [
            '201' => ['description' => 'Créé avec succès'],
            '400' => [
                'description' => 'Données invalides',
                'content' => [
                    'application/ld+json' => [
                        'example' => [
                            '@type' => 'ConstraintViolationList',
                            'violations' => [
                                [
                                    'propertyPath' => 'champNom',
                                    'message' => 'Message d\'erreur en français',
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

## Propriétés

### String Simple

```php
#[ApiProperty(
    description: 'Description du champ',
    example: 'Valeur exemple',
)]
public ?string $nom = null;
```

### Integer

```php
#[ApiProperty(
    description: 'Nombre de points',
    example: 575,
)]
public ?int $points = null;
```

### Float

```php
#[ApiProperty(
    description: 'Prix en euros',
    example: 4.99,
)]
public ?float $price = null;
```

### UUID (Identifiant)

```php
#[ApiProperty(
    identifier: true,
    description: 'Identifiant unique',
    example: '550e8400-e29b-41d4-a716-446655440000',
)]
public ?string $uuid = null;
```

### Date

```php
#[ApiProperty(
    description: 'Date de l\'événement',
    example: '2024-05-26 18:00:00',
)]
public ?\DateTimeImmutable $date = null;
```

### Boolean

```php
#[ApiProperty(
    description: 'Indique si actif',
    example: true,
)]
public ?bool $active = null;
```

### Relation

```php
#[ApiProperty(
    readableLink: true,
    writableLink: false,
    description: 'Pilote associé',
)]
public ?DriverResource $driver = null;
```

### Array

```php
#[ApiProperty(
    readableLink: true,
    writableLink: false,
    description: 'Liste des joueurs',
)]
public ?array $players = null;
```

---

## Codes de Réponse HTTP

| Code | Utilisation | Description |
|------|-------------|-------------|
| 200 | GET, POST (action) | Succès |
| 201 | POST (création) | Créé avec succès |
| 204 | POST/DELETE (no content) | Succès sans contenu |
| 400 | Validation | Données invalides |
| 403 | Authorization | Accès refusé |
| 404 | Not Found | Ressource non trouvée |
| 409 | Conflict | Conflit métier |
| 422 | Business Rule | Règle métier non respectée |

---

## Exemples d'Erreurs

### Erreur de Validation (400)

```php
'400' => [
    'description' => 'Données invalides',
    'content' => [
        'application/ld+json' => [
            'example' => [
                '@type' => 'ConstraintViolationList',
                'violations' => [
                    [
                        'propertyPath' => 'email',
                        'message' => 'L\'email doit être valide',
                    ],
                ],
            ],
        ],
    ],
],
```

### Erreur Métier (409)

```php
'409' => [
    'description' => 'Conflit',
    'content' => [
        'application/ld+json' => [
            'example' => [
                '@type' => 'hydra:Error',
                'hydra:description' => 'Ce pseudo est déjà utilisé',
            ],
        ],
    ],
],
```

### Accès Refusé (403)

```php
'403' => [
    'description' => 'Vous n\'êtes pas autorisé à effectuer cette action',
],
```

---

## Tags par Domaine

| Tag | Usage |
|-----|-------|
| Authentification | Login, register, password reset |
| Pilotes | Consultation pilotes F1 |
| Écuries | Consultation écuries F1 |
| Championnats | CRUD championnats |
| Joueurs | Profils et équipes |
| Enchères | Système d'enchères |
| Bonus | Achats de bonus |
| Portefeuille | Gestion crédits |
| Courses | Calendrier et résultats |
| Classements | Scores et positions |
| Duels | Face à face |
| Stratégies | Optimisation |
| Cosmétiques | Apparence |
| Performances | Statistiques |

---

## Exemples KOP Réalistes

### Pilotes

```php
'Max Verstappen'    // name
'Red Bull Racing'   // team
1                   // number
575                 // points
32500000            // value (crédits)
'#0600EF'           // color
```

### Écuries

```php
'Ferrari'           // name
'#DC0000'           // color
25000000            // minValue
```

### Dates

```php
'2024-05-26'                      // date
'2024-05-26 18:00:00'            // datetime
'2024-05-20T18:00:00+02:00'      // ISO 8601
```

### Crédits

```php
50000000            // budget
5000000             // bonus price
4.99                // pack price (euros)
```

### UUIDs

```php
'550e8400-e29b-41d4-a716-446655440000'   // format valide
```

---

## Checklist Rapide

Avant de valider votre documentation :

- [ ] Import de `Operation` et `RequestBody` si POST
- [ ] `description` sur `ApiResource`
- [ ] `summary` et `description` sur chaque opération
- [ ] `tags` approprié
- [ ] `example` sur CHAQUE propriété
- [ ] Codes de réponse documentés
- [ ] Exemples d'erreurs pour 400/409
- [ ] Descriptions en français
- [ ] Exemples réalistes F1

---

## Commandes

```bash
# Générer OpenAPI
php bin/console api:openapi:export -o public/openapi.json

# Voir dans Swagger
open https://kop.local/api/docs

# Valider
npx @redocly/cli lint public/openapi.json
```

---

## Ressources

- Guide complet : `/Users/splyy/Works/kop/api/docs/API_DOCUMENTATION_GUIDE.md`
- Audit : `/Users/splyy/Works/kop/api/docs/API_DOCUMENTATION_AUDIT.md`
- [API Platform Docs](https://api-platform.com/docs/core/openapi/)
