# King of Paddock (KOP) - API Backend

API Backend pour King of Paddock, une application de fantasy league motorsport où les utilisateurs gèrent des équipes de course virtuelles, participent à des enchères et s'affrontent sur la base de résultats de courses réels.

## Table des matières

- [Stack Technique](#stack-technique)
- [Architecture](#architecture)
  - [Domain-Driven Design (DDD)](#domain-driven-design-ddd)
  - [CQRS Pattern](#cqrs-pattern)
  - [API Platform](#api-platform)
- [Prérequis](#prérequis)
- [Installation](#installation)
  - [Configuration SSL locale](#configuration-ssl-locale)
  - [Installation des dépendances](#installation-des-dépendances)
  - [Configuration de la base de données](#configuration-de-la-base-de-données)
- [Commandes Make Disponibles](#commandes-make-disponibles)
  - [Docker](#docker)
  - [Composer](#composer)
  - [Base de données](#base-de-données)
  - [Assets (pnpm)](#assets-pnpm)
  - [Tests](#tests)
  - [Code Quality](#code-quality)
- [BackOffice](#backoffice)
- [Testing](#testing)
- [Stripe (Paiements)](#stripe-paiements)
  - [Configuration locale](#configuration-locale)
- [Fonctionnement Métier](#fonctionnement-métier)
  - [Workflow Championnat](#workflow-championnat)
  - [Import des Résultats de Course](#import-des-résultats-de-course)
- [Comptes de Test](#comptes-de-test)

## Stack Technique

- **Framework**: Symfony 6.4
- **PHP**: 8.3
- **Base de données**: MySQL 8
- **Search Engine**: Elasticsearch 7.17.28
- **API**: API Platform 3.2
- **Admin**: EasyAdmin 4
- **Authentification**: JWT (Lexik)
- **Paiements**: Stripe
- **Tests**: PestPHP
- **Code Quality**: PHP-CS-Fixer, PHPStan
- **Assets**: Webpack Encore + Tailwind CSS
- **Package Manager**: pnpm (monorepo)
- **Docker**: Environnement de développement local

## Architecture

### Domain-Driven Design (DDD)

L'API repose sur une architecture DDD organisée par feature/bounded context. Chaque domaine métier est isolé dans son propre namespace avec ses entités, value objects, repositories et services.

**Structure des bounded contexts** (`src/`):

```
src/
├── Admin/           # Interface d'administration (EasyAdmin)
├── Bid/             # Système d'enchères
├── Bonus/           # Gestion des bonus
├── Championship/    # Gestion des championnats
├── Cosmetic/        # Éléments cosmétiques
├── CreditWallet/    # Portefeuille de crédits virtuels
├── Driver/          # Entités et logique des pilotes
├── Duel/            # Système de duels
├── Parameter/       # Paramètres système
├── Performance/     # Calcul des performances
├── Player/          # Comptes joueurs
├── Race/            # Gestion des courses
├── Result/          # Résultats de courses
├── Season/          # Saisons de course
├── Shared/          # Utilitaires partagés
├── Strategy/        # Stratégies de course
├── Team/            # Gestion des écuries
└── User/            # Utilisateurs système
```

Chaque bounded context suit une structure cohérente:
- **Entity/**: Entités du domaine
- **Repository/**: Accès aux données
- **Service/**: Logique métier
- **Command/** & **Query/**: CQRS handlers

### CQRS Pattern

Le pattern **CQRS** (Command Query Responsibility Segregation) est appliqué pour séparer les opérations d'écriture (Commands) des opérations de lecture (Queries).

**Avantages**:
- Sécurité accrue des opérations
- Meilleure maintenabilité
- Scalabilité future
- Séparation claire des responsabilités

**Exemple**:
- `Championship/Command/CreateChampionshipCommand` - Création d'un championnat
- `Championship/Query/GetChampionshipQuery` - Lecture d'un championnat

### API Platform

L'API expose les ressources en REST grâce à API Platform. Les ressources sont annotées avec les attributs API Platform pour:
- Exposition automatique des endpoints CRUD
- Documentation OpenAPI automatique
- Serialization/Deserialization
- Validation
- Filtrage et pagination

**Documentation API**: `/api/docs` (interface Swagger UI)

## Prérequis

- **Docker** et **Docker Compose**
- **pnpm** 9+ (`npm install -g pnpm`)
- **Node.js** 20+ (géré via `nvm use`)
- **mkcert** (pour les certificats SSL locaux)

## Installation

### Configuration SSL locale

Les certificats SSL sont **indispensables** pour le bon fonctionnement de NGINX.

1. Installer mkcert:
```bash
make install-mkcert
```

2. Générer le certificat:
```bash
make regenerate-mkcert
```

**En cas d'erreur** (`ERROR: failed to save certificate key: open config/docker/images/nginx/localhost.key: is a directory`):
```bash
rm -rf config/docker/images/nginx/localhost.*
make regenerate-mkcert
```

**Partage du certificat sur Android**:

Pour accéder au domaine en SSL depuis un téléphone Android:
1. Copier le contenu du fichier `config/docker/images/nginx/rootCA.pem` sur le téléphone
2. Aller dans **Paramètres** → **Sécurité** → **Autres paramètres** → **Cryptage et références** → **Installer depuis le stockage**
3. Sélectionner le fichier `rootCA.pem` et lui donner un nom
4. Redémarrer le navigateur
5. Accéder à `https://192.168.x.x`

### Installation des dépendances

**Depuis la racine du monorepo**:
```bash
# Installation complète (API + App)
make install

# Démarrer tous les services
make up

# Ou uniquement l'API
make api-up
```

**Depuis le dossier `api/`**:
```bash
# Démarrer les services Docker
make docker-up

# Installer les dépendances PHP
make composer-install

# Installer les dépendances npm et compiler les assets
pnpm install
pnpm run build

# Ou via Make
make npm-install
make npm-build
```

### Configuration de la base de données

1. Générer les clés JWT:
```bash
docker compose exec php gosu kop php bin/console lexik:jwt:generate-keypair
```

2. Créer et peupler la base de données:
```bash
# Depuis la racine
make api-db

# Ou depuis api/
make db-dev
```

Cette commande:
- Supprime la base existante
- Crée une nouvelle base
- Exécute les migrations
- Charge les fixtures de développement

**URL d'accès**: `https://kop.local` (configurer `/etc/hosts` si nécessaire)

## Commandes Make Disponibles

Utiliser `make help` pour lister toutes les commandes disponibles.

### Docker

```bash
make docker-build         # Construire les images Docker
make docker-up           # Démarrer les services Docker
make docker-down         # Arrêter les services Docker
make docker-down-volumes # Arrêter et supprimer les volumes
make docker-restart      # Redémarrer les services
```

### Composer

```bash
make composer-install      # Installer les packages
make composer-update       # Mettre à jour les packages
make composer-require ARGS="vendor/package"  # Ajouter un package
make composer-require-dev ARGS="vendor/package"  # Ajouter un package dev
make composer-remove ARGS="vendor/package"   # Supprimer un package
```

### Base de données

```bash
make db-dev              # Réinitialiser la BDD de dev avec fixtures
make db-test             # Réinitialiser la BDD de test avec fixtures
make fixtures-update     # Recharger uniquement les fixtures (dev)
make test-fixtures-update # Recharger uniquement les fixtures (test)
```

### Assets (pnpm)

```bash
# Via pnpm (recommandé)
pnpm install             # Installer les dépendances
pnpm run build           # Build de production
pnpm start               # Dev avec watch mode

# Via Make (utilise Docker)
make npm-install         # Installer les dépendances
make npm-build           # Build de production
make npm-start           # Dev avec watch mode
make assets-install      # Installer les assets Symfony
```

### Tests

```bash
make tests               # Lancer tous les tests
make pest-run            # Lancer tous les tests PestPHP (compact, parallel)
make pest-run-verbose    # Tests avec détails complets
make pest-run-unit       # Tests unitaires uniquement
make pest-run-integration # Tests d'intégration
make pest-run-application # Tests applicatifs
make pest-run-architecture # Tests d'architecture
make pest-run-one NAME="tests/Unit/MyTest.php"  # Test spécifique
make pest-bail           # Arrêter au premier échec
make pest-dirty          # Relancer uniquement les tests modifiés
make pest-retry          # Relancer les tests échoués
make pest-profile        # Profiler les tests lents
make pest-todos          # Lister les tests TODO
```

### Code Quality

```bash
make cs-check            # Vérifier le code style (PHP-CS-Fixer)
make cs-fix              # Corriger le code style
make stan                # Analyse statique (PHPStan level 6)
make security-check      # Vérifier les vulnérabilités de sécurité
```

### Setup complet

```bash
make setup-dev           # Setup dev rapide
make setup-dev-full      # Setup dev complet (avec composer + npm)
make setup-prod          # Setup production
```

## BackOffice

Interface d'administration construite avec **EasyAdmin 4**.

**URL**: `/admin`

**Fonctionnalités principales**:
- Gestion des championnats, courses, pilotes, écuries
- Import des résultats de courses (CSV)
- Génération des performances
- Gestion des utilisateurs et joueurs
- Configuration des paramètres système

## Testing

Le projet utilise **PestPHP** pour tous les types de tests:

- **Tests unitaires** (`@group unit`): Logique métier isolée
- **Tests d'intégration** (`@group integration`): Interactions avec la BDD
- **Tests applicatifs** (`@group application`): Tests end-to-end API
- **Tests d'architecture** (`@group architecture`): Respect des règles DDD/CQRS

**Configuration**: `Pest.php` et `phpunit.xml.dist`

**Exemple de lancement**:
```bash
# Tous les tests en parallèle
make pest-run

# Seulement les tests d'un groupe
make pest-run-unit

# Un test spécifique
make pest-run-one NAME="tests/Unit/Championship/ChampionshipTest.php"
```

## Stripe (Paiements)

L'application utilise **Stripe** pour gérer les paiements et abonnements.

### Configuration locale

Pour tester les webhooks Stripe en local, utiliser le **Stripe CLI**:

1. **Créer une clé locale**:
```bash
docker-compose run stripe-cli listen --forward-to nginx:80/api/payment/confirm
```
Cliquer sur le lien dans la console pour valider la création de la clé.

2. **Lancer le listener**:
```bash
docker-compose run stripe-cli listen --skip-verify \
  --forward-to nginx:80/api/payment/confirm \
  --api-key {limited_key}
```

3. **Simuler un webhook** (pour tests):
```bash
docker-compose run stripe-cli trigger checkout.session.completed \
  --api-key {limited_key}
```

## Fonctionnement Métier

### Workflow Championnat

Le cycle de vie d'un championnat suit plusieurs étapes automatisées par des commandes cron.

#### 1. Lancement du championnat

**Déclenchement**: Pour chaque championnat ayant atteint le nombre maximum de joueurs

**Action**: Mise à jour du statut pour démarrer les enchères

**Statut**: `CREATED (1)` → `BID_IN_PROGRESS (2)`

**Cron**: `app:championship:start`

---

#### 2. Assignation des résultats d'enchère

**Déclenchement**: Après la fin du tour d'enchères

**Action**: Assignation des pilotes/écuries aux joueurs ayant la plus haute enchère
- En cas d'égalité, le joueur ayant enchéri en premier gagne

**Statut**: `BID_IN_PROGRESS (2)` → `BID_RESULT_PROCESSED (3)`

**Cron**: `app:championship:assign-item`

---

#### 3. Assignation automatique (AFK et budget insuffisant)

**Déclenchement**: Après assignation des résultats aux gagnants

**Actions**:
- Assignation automatique de pilotes et écuries aux joueurs n'ayant plus le budget suffisant pour enchérir
- Assignation automatique aux joueurs **AFK** (n'ayant pas fait d'enchères sur les 2 derniers tours)
- **Si tous les joueurs ont 2 pilotes et 1 écurie**: passage à l'étape d'assignation des courses
- **Sinon**: incrémentation du tour d'enchères et retour au statut d'enchère

**Statut**:
- `BID_RESULT_PROCESSED (3)` → `NEED_TO_ASSIGN_RACES (4)` (si complet)
- `BID_RESULT_PROCESSED (3)` → `BID_IN_PROGRESS (2)` (si nouveau tour)

**Cron**: `app:championship:assign-auto`

---

#### 4. Assignation des courses

**Déclenchement**: Après la fin des enchères

**Actions**:
- Assignation des prochaines courses après **date actuelle + 7 jours** (pour laisser le temps aux joueurs de définir leur stratégie)
- **Annulation du championnat** s'il reste moins de 4 courses

**Statut championnat**: `NEED_TO_ASSIGN_RACES (4)` → `ACTIVE (5)`

**Statut première course**: `CREATED (1)` → `ACTIVE (2)`

**Cron**: `app:championship:assign-races`

---

#### 5. Fin des stratégies

**Déclenchement**: Pour chaque championnat actif ayant une course active avec date de fin de stratégie dépassée

**Actions**:
- Soustraction d'une utilisation au compteur d'utilisation de pilote pour les stratégies (si pas de pilote sélectionné, soustraction en priorité sur le pilote 1)
- Affectation **aléatoire** d'un pilote pour le duel si le joueur n'en a pas sélectionné
- Soustraction d'une utilisation au compteur de pilote pour les duels
- Changement du statut de la course à "En attente de résultat"

**Statut course**: `ACTIVE (2)` → `WAITING_RESULT (3)`

**Cron**: `app:championship:end-strategy`

---

### Import des Résultats de Course

**Action**: Manuelle depuis le Back-Office

L'import des résultats s'effectue pour **1 course sur une saison donnée**.

#### Format du fichier CSV

| Pilotes         | Qualification | Sprint | 0 | 1 | 2 | 3 | 4 | 5 | 6 | 7 | 8 | 9 | {numéro tour} |
|-----------------|---------------|--------|---|---|---|---|---|---|---|---|---|---|---------------|
| Max VERSTAPPEN  | 1             | 1      | 1 | 1 | 1 | 2 | 1 | 2 | 1 | 2 | 1 | 2 | 1             |
| Charles LECLERC | 2             |        | 2 | 2 | 2 | 1 | 2 | 1 | 2 | 1 | 2 | 1 | 2             |

**Note**: La colonne Sprint peut être vide si la course n'a pas d'épreuve sprint.

#### Processus d'import

1. **Enregistrement des résultats par tour**: Position du pilote pour chaque tour de course
2. **Calcul des performances générales**: Performance des pilotes et écuries
3. **Mise à jour du statut**: La course passe au statut "Résultat effectué"

**Statut course**: `WAITING_RESULT (3)` → `RESULT_PROCESSED (4)`

---

#### Génération des performances sur les championnats

**Action**: Manuelle depuis le Back-Office (après l'import)

Une fois l'import de résultat effectué, il faut **générer les performances** pour les championnats.

**Cette opération**:
- Calcule les performances avec les bonus utilisés
- Calcule les scores/points/positions des joueurs sur les différents championnats

**⚠️ ATTENTION**: Une fois les performances générées, il n'est **actuellement plus possible** de supprimer les résultats importés car nous ne traçons pas les "mouvements" de score/points/positions des joueurs.

**Il est donc nécessaire de s'assurer de la validité des résultats importés avant de générer les performances.**

---

## Comptes de Test

### Administrateurs

| Rôle                 | Commentaire            | Email              | Mot de passe |
|----------------------|------------------------|--------------------|--------------|
| Super Administrateur | Tous les droits        | admin+super@kop.fr | password     |

---

## Développement

### Commits

Les commits doivent suivre la norme **Conventional Commits**.

**Format**: `<type>(<scope>): <description>`

**Exemples**:
- `feat(championship): add automatic assignment for AFK players`
- `fix(bid): correct tie-breaking logic in auctions`
- `docs(readme): update installation instructions`

### Git Hooks

Configurer les hooks locaux:
```bash
make setup-hooks
```

---

## Déploiement

Cette API fait partie d'un monorepo et est déployée en coordination avec l'application frontend Next.js.

Voir le `CLAUDE.md` à la racine pour plus d'informations sur l'architecture globale du projet.
