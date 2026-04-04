# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## RÈGLE ABSOLUE — Environnement local = bac à sable sans limites

Cet environnement est **uniquement local**, jamais déployé en prod sans décision explicite du propriétaire.
**Ne jamais dire "laisse ça au freelance" ou "il faudrait soumettre ça au freelance" ou équivalent.**
On explore, on casse, on refond ce qu'on veut. C'est le but. Aucune auto-censure sur le périmètre des modifications.

## Project Overview

**King of Paddock (KOP)** - A motorsport fantasy league application where users manage virtual racing teams, participate in auctions, and compete based on real race results.

## Repository Structure

```
kop/
├── api/                    # Symfony 6.4 backend (PHP 8.3)
│   ├── src/               # PHP source code (DDD architecture)
│   ├── config/            # Symfony configuration + Docker configs
│   ├── public/            # Web root + compiled assets
│   ├── templates/         # Twig templates (emails, admin)
│   ├── Makefile           # API-specific commands (60+ targets)
│   └── docker-compose.yml # Backend services (MySQL, PHP, Nginx, ES)
│
├── app/                    # Next.js 14 frontend (TypeScript)
│   ├── app/               # Next.js App Router pages
│   ├── components/        # React components
│   ├── actions/           # Server Actions
│   ├── Makefile           # App-specific commands
│   └── docker-compose.yml # Frontend container
│
├── Makefile               # Root-level orchestration commands
├── pnpm-workspace.yaml    # pnpm workspace configuration
└── package.json           # Root package with workspace scripts
```

## Tech Stack

### Backend (API)
- **Framework**: Symfony 6.4 with API Platform
- **Language**: PHP 8.3
- **Database**: MySQL
- **Search**: Elasticsearch
- **Testing**: PestPHP
- **Architecture**: DDD + CQRS pattern
- **Admin**: EasyAdmin 4
- **Payments**: Stripe

### Frontend (App)
- **Framework**: Next.js 14 (App Router)
- **Language**: TypeScript
- **Styling**: Tailwind CSS
- **UI Components**: shadcn/ui (Radix UI)
- **Forms**: react-hook-form + Zod validation

## Common Commands

### Quick Start
```bash
# Install all dependencies
make install

# Start all services
make up

# Stop all services
make down
```

### Development
```bash
# API commands
make api-up          # Start API Docker services
make api-down        # Stop API Docker services
make api-db          # Reset database with fixtures
make api-test        # Run PestPHP tests

# App commands
make app-up          # Start App Docker services
make app-down        # Stop App Docker services
make app-dev         # Start local dev server (no Docker)
make app-build       # Build for production
```

### Using pnpm
```bash
pnpm install                    # Install all workspace dependencies
pnpm --filter @kop/api build    # Build API assets
pnpm --filter @kop/app dev      # Start App dev server
pnpm -r run build               # Build all projects
```

## Development Guidelines

### Code Style
- **PHP**: PHP-CS-Fixer (run `make cs-fix` in api/)
- **TypeScript/JS**: ESLint + Prettier
- **Commits**: Conventional Commits format

### API Architecture (DDD)
The backend uses Domain-Driven Design organized by feature:
- `src/Championship/` - Championship management
- `src/Driver/` - Driver entities and logic
- `src/Race/` - Race management
- `src/Player/` - Player accounts
- `src/Team/` - Team management
- `src/Bid/` - Auction/bidding system
- `src/CreditWallet/` - Virtual currency
- `src/Shared/` - Shared utilities

Commands (write) and Queries (read) follow CQRS pattern.

### Testing
- Backend: PestPHP for unit, integration, and architecture tests
- Run `make api-test` or specific groups via api/ Makefile

## Environment Setup

### Prerequisites
- Node.js 20 (use `nvm use`)
- pnpm 9+ (`npm install -g pnpm`)
- Docker and Docker Compose
- PHP 8.3 (optional, for local non-Docker development)

### First-time Setup
1. Clone the repository
2. Run `make install`
3. Run `make up`
4. Run `make api-db` to set up the database
5. Access API at https://kop.local (configure hosts file)
6. Access App at http://localhost:3000

---

## Environnement local personnel (bac à sable d'apprentissage)

Cet environnement est distinct du staging géré par le développeur freelance.
Il tourne entièrement en local sur la machine (WSL2 + Docker Desktop).
**Rien ici n'est déployé en prod.** Toute modification est à faire reviewer par le dev avant mise en production.

### Adresses locales

| Service | Adresse | Identifiants |
|---|---|---|
| App joueur (Next.js) | https://localhost:3000 | voir fixtures |
| Admin (EasyAdmin) | https://kop.local/admin | admin+super@kop.fr / password |
| Mailcatcher (emails) | http://localhost:1080 | aucun |
| API (Symfony) | https://kop.local | — |

> Chrome affiche une alerte de sécurité sur les adresses en HTTPS local — c'est normal (certificat auto-signé). Cliquer sur "Paramètres avancés" puis "Continuer vers le site (non sécurisé)".

### Comptes de test

- **Admin** : `admin+super@kop.fr` / `password` (EasyAdmin uniquement, pas l'app joueur)
- **Compte perso** : `alexisbissuel.dev@gmail.com` / `TestKOP!1996`
- **Joueurs de test** (créés manuellement, mot de passe `password` pour tous) :
  - `joueur1@kop.fr`
  - `joueur2@kop.fr`
  - `joueur3@kop.fr`
  - `joueur4@kop.fr`
- **Joueurs fixtures Faker** : emails aléatoires, mot de passe `password` pour tous

> Ces comptes (hors fixtures) ne survivent pas à un `make api-db` — les recréer via le script SQL si besoin.

### Premier lancement (mise en place initiale — déjà faite)

Ces étapes ont déjà été réalisées une fois. Les noter ici pour référence si besoin de repartir de zéro.

**1. Fichier hosts (une seule fois par machine)**

Sous WSL :
```bash
echo "127.0.0.1 kop.local" | sudo tee -a /etc/hosts
```

Sous Windows (PowerShell en administrateur) :
```powershell
Add-Content -Path "C:\Windows\System32\drivers\etc\hosts" -Value "127.0.0.1 kop.local"
```

**2. Clés JWT (une seule fois)**
```bash
mkdir -p api/config/jwt
openssl genpkey -out api/config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096 -pass pass:c7ef1555af178d1583cc088bd26f626b960dc82a35c52a2d3c3e3b0023be256d
openssl pkey -in api/config/jwt/private.pem -out api/config/jwt/public.pem -pubout -passin pass:c7ef1555af178d1583cc088bd26f626b960dc82a35c52a2d3c3e3b0023be256d
```

**3. Certificats SSL Nginx (une seule fois)**
```bash
openssl req -x509 -nodes -days 3650 -newkey rsa:2048 -keyout api/config/docker/images/nginx/localhost.key -out api/config/docker/images/nginx/localhost.crt -subj "/CN=kop.local" -addext "subjectAltName=DNS:kop.local,DNS:localhost"
```

**4. Construction de l'image Docker PHP (une seule fois)**
```bash
cd api && make docker-build
```

**5. Installation des dépendances (une seule fois)**
```bash
pnpm install
```

**6. Initialisation de la base de données (une seule fois, ou pour repartir propre)**
```bash
make api-db
```
> Si erreur "Access denied" sur MySQL : `cd api && docker-compose -p kop down --volumes` puis relancer.

**7. Compilation des assets admin (une seule fois, ou après modif CSS/JS admin)**
```bash
pnpm run build
```

### Lancement quotidien (après la mise en place initiale)

Ouvrir deux terminaux (trois si on teste les notifications programmées) :

**Terminal 1 — Backend**
```bash
cd /home/alek6dev/kop-app-main
make api-up
```

**Terminal 2 — Frontend**
```bash
cd /home/alek6dev/kop-app-main
make app-dev
```

**Terminal 3 — Worker notifications (optionnel, uniquement pour tester la programmation)**
```bash
cd /home/alek6dev/kop-app-main
make api-notifications-worker   # tourne toutes les 60s, Ctrl+C pour arrêter
# OU en one-shot :
make api-publish-notifications
```

Puis ouvrir https://localhost:3000 (app) et https://kop.local/admin (admin) dans Chrome.

### Arrêter l'environnement
```bash
make api-down
# Ctrl+C dans le terminal du frontend
```

### Repartir avec une base de données propre
```bash
cd api && docker-compose -p kop down --volumes
cd .. && make api-up
make api-db
```

---

## Contexte et objectifs de l'exploration du code

### Pourquoi cette exploration

Le propriétaire du projet (profil non-technique, junior en développement) souhaite comprendre son application de l'intérieur. Le projet a coûté ~100k€ sur 5 ans via une agence, puis a été repris par un développeur freelance Symfony. L'objectif est de :

1. **Comprendre ce qu'on possède** — architecture, domaines métier, flux de données
2. **Être capable de proposer des améliorations** au freelance en connaissance de cause
3. **Apprendre à vibecoder** sur le projet en toute sécurité (environnement local uniquement, review obligatoire avant prod)
4. **Progresser techniquement** pour ne plus être dépendant à 100% des prestataires

### Plan d'exploration (sessions)

- **Session 1 — Vue d'ensemble** ✅ : structure des dossiers, rôle de chaque partie, schéma de communication backend/frontend
- **Session 2 — Backend Symfony en détail** ✅ : organisation DDD en 3 couches (Domain/Application/Infrastructure), pattern CQRS (Commands/Queries/Handlers), cycle de vie d'un championnat, lecture complète du AddBidCommandHandler (règles des enchères)
- **Session 3 — Frontend Next.js en détail** ✅ : zones (guest/logged/hub/legals), pages vs composants vs layouts, lecture de la page sillyseason (4 appels API simultanés, Server Components), Server Actions (addBids_action), lien avec CQRS backend
- **Session 4 — Infra** ✅ : Docker (containers = mini-machines), 3 environnements (local/staging/prod), chemin d'une feature vers la prod, config Git (origin = repo perso, upstream = repo officiel King-Of-Paddock/kop-app)
- **Session 5 — Première feature** ✅ : fixes Docker PHP, responsive desktop, password toggle, fix inscription

### Règles de collaboration

- Tout le code produit ici est expérimental, jamais déployé sans review du freelance
- S'adresser au propriétaire comme à un développeur junior : expliquer les termes, proposer par petites étapes
- Le propriétaire a un profil PO/QA/chef de projet — il arrive avec des specs précises, pas besoin de cadrer les features à sa place
- Zones à ne pas toucher sans le freelance : auth (JWT), Stripe, Firewall/Security, migrations de base de données
- Zones safe pour expérimenter : frontend (pages, composants, styles), nouvelles pages, améliorations UX
- Pour les grosses features backend : on peut vibecoder en local pour explorer, le dev reviewe avant tout passage en prod

### Ce qu'on a appris sur l'architecture (Session 2)

- Chaque domaine est découpé en 3 couches : `Domain` (règles pures), `Application` (actions), `Infrastructure` (BDD + API)
- CQRS : tout ce qui modifie = `Command` + `CommandHandler`, tout ce qui lit = `Query` + `QueryHandler`
- Les règles métier des enchères sont dans `api/src/Bid/Application/Command/AddBid/AddBidCommandHandler.php`
- Les erreurs métier sont centralisées dans des fichiers `Exception` par domaine
- Un championnat passe par 8 états définis dans `ChampionshipStatusEnum` (CREATED → BID_IN_PROGRESS → ... → OVER)
- Fix appliqué : `Header.tsx` ligne 46 — `creditWallet?.credit ?? 0` pour éviter le crash si le portefeuille est null

### Config Git (mise en place en session 4, renommage en session 6)

- `perso` → `https://github.com/Alek6-dev/kop-exploration.git` (repo perso — push quotidien ici)
- `officiel` → `https://github.com/King-Of-Paddock/kop-app` (repo du freelance — PR uniquement)
- Pour récupérer le code du freelance : `git pull officiel main`
- Le dossier vient d'un **vrai clone** du repo officiel (pas un ZIP) depuis le 30/03/2026

### Workflow pour soumettre une feature au freelance

Ce workflow est à suivre à chaque fois qu'une feature est prête à être soumise en review.

**Au quotidien (brouillon)**
- On travaille sur `main` en local
- Push de sauvegarde en fin de session : `git push perso main`

**Quand une feature est prête**

1. Identifier les fichiers concernés par la feature (uniquement les nouveaux fichiers et modifications liées)
2. Vérifier qu'aucun fichier supprimé ou sans rapport ne se glisse dedans
3. Créer une branche propre depuis le repo officiel :
   ```bash
   git checkout -b feat/nom-de-la-feature officiel/main
   ```
4. Copier uniquement les fichiers de la feature depuis `main` :
   ```bash
   git checkout main -- chemin/fichier1 chemin/fichier2
   ```
5. Vérifier ce qui va être commité : `git status`
6. Commiter et pusher sur le repo officiel :
   ```bash
   git commit -m "feat: description claire"
   git push officiel feat/nom-de-la-feature
   ```
7. Créer la PR sur GitHub : `github.com/King-Of-Paddock/kop-app` → Pull requests → New pull request
   - base : `main` / compare : `feat/nom-de-la-feature`
   - Titre et description clairs pour le freelance
8. Prévenir le freelance par message qu'une PR est ouverte
9. Revenir sur `main` : `git checkout main`

**Règles importantes**
- Ne jamais pusher `main` directement sur `officiel`
- Une branche = une feature (pas une branche permanente, pas une branche par session)
- Toujours vérifier `git status` avant de commiter sur une branche officielle
- Le freelance voit uniquement ce qui est pushé sur `officiel` — `perso` est invisible pour lui

### Commandes de remise en route après un clone / recréation complète

Si jamais on repart de zéro (nouveau clone), dans l'ordre :
1. `pnpm install` — dépendances frontend
2. `make composer-install` — dépendances PHP
3. `docker exec kop-php-1 gosu kop php /var/www/kop/bin/console assets:install` — assets admin EasyAdmin
4. Régénérer les certificats SSL Nginx (voir section "Premier lancement")
5. `make api-db` — base de données

### Chantiers identifiés (audit EVALUATE.md)

Par ordre de priorité :
1. **Cache Redis** côté backend — réduire la latence
2. **Déploiement** — moderniser le pipeline hérité de l'agence
3. **Rechargements de page** — remplacer `window.location.reload()` par `router.refresh()`
4. **PWA / notifications push** — réengager les joueurs
5. **App native (stores)** — via Capacitor (wrapper de l'app Next.js existante), option la plus économique

---

## FEATURE EN COURS — Import automatique saison F1 2026

### Objectif
Deux commandes Symfony Console qui permettent de bootstrapper la saison et d'importer les résultats après chaque GP, sans saisie manuelle.

### Commandes à créer

**`php bin/console kop:f1:sync-season 2026`** — à lancer une fois en début de saison
- Crée/met à jour : `Season`, `Race`, `Team`, `Driver`, `SeasonRace`, `SeasonTeam`
- Calcule `limitStrategyDate` = heure début qualifs (weekend normal) OU heure début course sprint (weekend sprint)
- `minValue` pilotes/équipes : **laisser un espace prévu**, sera calculé via formule basée sur les résultats des 3-4 derniers GP (forme actuelle). À implémenter dans une session dédiée.

**`php bin/console kop:f1:import-race 2026 {numéro_GP}`** — à lancer après chaque course
- Crée : `Result`, `ResultLap` (positions tour par tour), `DriverPerformance`, `TeamPerformance`
- Gère les 3 types de sessions : QUALIFICATION (type=2), SPRINT (type=3), RACE (type=1)

### Source de données — F1 Live Timing UNIQUEMENT
URL de base : `https://livetiming.formula1.com/static`

**OpenF1 est BANNI** — données obsolètes (dernier GP = Singapour 2025 en mars 2026), totalement non fiable.

Structure des endpoints F1 Live Timing (même source que le pipeline Python `/home/kop/`) :
```
/{year}/{EventName}_{Country}/                    ← dossier par GP
  /Race/                                          ← session course
    DriverList.json                               ← pilotes (numéro, TLA, prénom, nom, équipe)
    TimingData.json                               ← positions par tour
    LapSeries.json                                ← série de tours
    LapCount.json                                 ← nombre de tours
    SessionStatus.json                            ← statut session
    FinalClassification.json                      ← classement final
  /Qualifying/                                    ← session qualifications
    FinalClassification.json
  /Sprint/                                        ← session sprint (si applicable)
    FinalClassification.json
  /SprintQualifying/                              ← qualifs sprint (si applicable)
Index des événements : https://livetiming.formula1.com/static/{year}/Index.json
```

Le pipeline Python de référence est dans `/home/kop/` avec ses scripts :
- `explore_f1_timing.py` — explore les sessions disponibles
- `fetch_session_results.py` — récupère classements (status 1088 = "Finished", autres = "DNF")
- `build_lap_positions.py` — parse lap_series.json → positions tour par tour
- `build_race_timing.py` — inverse raw_timing.csv

### Modèle de données — rappel des entités clés

**SeasonRace** (champs critiques) :
- `date` — heure départ course
- `qualificationDate` — heure début qualifs
- `sprintDate` — heure début course sprint (nullable)
- `limitStrategyDate` = qualificationDate (weekend normal) ou sprintDate (weekend sprint)
- `laps` — nombre de tours

**ResultLap** :
- `noLap` — numéro du tour
- `place` — position (string, ex: "1", "2"...)
- `type` — TypeResultEnum : NORMAL=1, QUALIFICATION=2, SPRINT=3
- `driver` → Driver
- `team` → Team
- `result` → Result

**DriverPerformance** (champs à alimenter) :
- `position` — position finale course
- `qualificationPosition` — position en grille
- `sprintPosition` — position sprint (nullable)
- `qualificationPoints` — QualificationPositionPointEnum
- `racePoints` — RacePositionPointEnum
- `sprintPoints` — SprintPositionPointEnum (nullable)
- `positionGain` = qualificationPosition - racePosition (positif = a gagné des places)
- `score`, `scoreWithBonus`, `multiplier` — calculés après

### Architecture DDD — où mettre le code
Pattern existant : chaque domaine dans `api/src/{Domain}/`
- Domain/ — entités pures, pas de dépendance externe
- Application/Command/ — CommandHandler (écriture)
- Application/Query/ — QueryHandler (lecture)
- Infrastructure/ — Doctrine entities, API Platform resources

**Les commandes Symfony Console** vont dans : `api/src/Shared/Infrastructure/Console/` (ou créer `api/src/Season/Infrastructure/Console/` si on veut respecter le DDD strict — à décider au démarrage de la session).

### Remplacement de pilote — NE PAS TOUCHER
Géré manuellement en admin. Trop complexe et source de bugs critiques (cas vécu en 2025 : pilote remplacé définitivement → bugs dans championnats actifs, enchères, résultats historiques). Chantier séparé à planifier.

### Ce qui a déjà été fait dans cette repo
- Fix entrypoint Docker PHP (exit code 9 + permissions log) → `api/config/docker/images/php-fpm/entrypoint.sh`
- Responsive desktop : conteneur max-width 480px centré → `app/app/layout.tsx`, `app/styles/_base-custom.css`, `app/app/_components/Menu.tsx`
- Composant `PasswordInput` avec toggle œil → `app/components/custom/password-input.tsx` + 4 formulaires mis à jour
- Fix page inscription : paramètre `user_confirmation_by_admin` manquant → optional chaining + insertion en BDD
- Comptes de test créés directement en BDD (voir section "Comptes de test")
- Import F1 Live Timing : `kop:f1:sync-season` + `kop:f1:import-race` → `api/src/Shared/Infrastructure/Console/F1/`
- Fix timezone heure limite stratégie : composant `LocalDateTime` client-side → `app/components/custom/local-datetime.tsx`
- Archivage des entités (Race, Season, Team, Driver, Result) : `ArchivableTrait` + action "Archiver" dans EasyAdmin + filtre "Archivé"

---

## État de la feature F1 import — ✅ SCORING VALIDÉ (session du 01/04/2026)

### Commandes disponibles

**`php bin/console kop:f1:sync-season 2026`** — à lancer une fois en début de saison
- Source calendrier : **`api.jolpi.ca/ergast`** (miroir communautaire Ergast) → 22 GP 2026 avec toutes les dates en UTC
- Source pilotes/équipes : **F1 Live Timing** `DriverList.json` → seulement quand le GP est publié (quelques jours avant)
- Crée : `Season`, `Race`, `SeasonRace` (avec dates race/qual/sprint/limitStrategy), `Team`, `Driver`, `SeasonTeam`
- `laps` = 0 par défaut → **à renseigner manuellement en admin avant chaque championnat** (ne pas estimer : risque d'erreur critique sur le scoring)
- Idempotent : relancer ne crée pas de doublons

**`php bin/console kop:f1:import-race 2026 {numéro_GP}`** — à lancer après chaque course
- Crée : `Result`, `ResultLap` (qual + sprint + course tour par tour), `DriverPerformance`, `TeamPerformance`
- GPO calculé tour par tour (cumul des gains positifs uniquement)
- DNF : GPO conservé jusqu'au dernier tour connu, position officielle finale substituée pour les finishers (gestion pénalités)
- Score équipe : pilote DNF compte comme `nbDrivers` (22 en 2026, dynamique) pour le calcul du multiplicateur
- Testé et validé sur GP#1 Australie, GP#2 Chine (sprint), GP#3 Japon ✅

### Sources de données
- **Calendrier** : `https://api.jolpi.ca/ergast/f1/{year}/races.json?limit=500`
- **Pilotes/équipes/résultats** : `https://livetiming.formula1.com/static` (F1 Live Timing)
- **OpenF1 est BANNI** — données obsolètes

### Ce qui reste à faire sur ce pipeline
- `minValue` pilotes/équipes : formule basée sur les résultats des 3-4 derniers GP (forme actuelle) — session dédiée

---

## FEATURE EN COURS — Centre de notifications in-app

Développement découpé en **3 blocs** à traiter dans des sessions séparées.

---

### SPEC COMPLÈTE

#### Contexte
Les résultats de course sont figés après génération. Si une pénalité F1 arrive le lendemain, impossible de corriger les scores dans les championnats et d'en informer les joueurs. Le centre de notifications résout le problème de communication, et sera branché sur le système de recalcul quand celui-ci sera développé.

#### Point d'entrée dans l'app
- Icône profil en haut à droite du hub joueur (déjà existante)
- **Rond rouge** sur cette icône tant qu'il existe des notifications non lues — pas de numéro, juste le rond
- La page Notifications est accessible depuis la section profil

#### La page Notifications
- Liste triée par date décroissante
- Chaque ligne : titre + indicateur visuel lu/non lu
- Clic sur une notif → ouvre le corps complet + passe en "lue"
- Bouton **"Tout marquer comme lu"** — vide le rond rouge sans tout ouvrir
- Suppression individuelle possible par le joueur
- Expiration automatique après **6 mois**, suppression silencieuse

#### Structure d'une notification
- **Titre** — affiché dans la liste
- **Corps** — affiché à l'ouverture
- **Liens interactifs** optionnels dans le corps : texte souligné en doré, redirige vers un élément de l'app (championnat, boutique, fonctionnalité). Ex : *"Ton score dans [Championship Été 2026] a changé"*
- **Catégorie** — détermine la couleur/icône (tons app : doré, gris foncé, blanc — à affiner en phase design)

#### Types de notifications

**Système — générées automatiquement :**
| Type | Destinataires | Déclencheur |
|---|---|---|
| Résultat GP publié | Tous | Import résultats |
| Correction GP — générique | Joueurs non impactés directement | Recalcul résultats |
| Correction GP — détaillée | Joueurs avec championnat impacté | Recalcul résultats |
| Crédit reçu | Joueur concerné | Attribution crédit |

La notif de correction détaillée liste dans son corps **tous les championnats impactés** + delta score/récompenses dans chacun — une seule notif, pas une par championnat.

**Éditorial — créées manuellement depuis EasyAdmin :**
Programme week-end, rappel stratégie, nouvelle fonctionnalité, boutique, mise à jour app, contenu libre.

#### La popup
Réservée aux événements à fort impact (< 10/an) : correction championnat, nouvelle fonctionnalité majeure, mise à jour app.
- S'affiche à la **première ouverture** après l'événement
- Scrollable si contenu long
- Fermée via croix en haut à droite ou bouton "Fermer" en bas
- Non bloquante
- Pointe vers la notif correspondante dans la page Notifications
- Ne se réaffiche pas après fermeture

#### Administration EasyAdmin — notifs éditoriales
- Titre + corps avec mise en forme basique (gras, italique, liens)
- Type / catégorie
- Ciblage : tous les joueurs **ou** sélection manuelle (liste + recherche par nom/email/pseudo)
- Programmation : immédiate **ou** date/heure future
- Historique des notifs envoyées, annulation possible si pas encore envoyée

#### Hors scope pour cette feature
- Notifications push (sujet séparé)
- Recalcul des résultats (feature séparée, à brancher ensuite)
- Personnalisation des couleurs (décision UI phase design)
- Automatisation notifs "mise à jour app"

---

### BLOC 1 — Structure de données + Administration ✅ TERMINÉ (02/04/2026)

**Fichiers créés :**
- `api/src/Notification/Domain/Enum/NotificationTypeEnum.php` — enum string (SYSTEM_RESULT_PUBLISHED, SYSTEM_CORRECTION_GENERIC, SYSTEM_CORRECTION_DETAILED, SYSTEM_CREDIT, EDITORIAL)
- `api/src/Notification/Infrastructure/Doctrine/Entity/Notification.php` — entité principale
- `api/src/Notification/Infrastructure/Doctrine/Entity/NotificationRead.php` — table lu/non lu par joueur
- `api/src/Notification/Infrastructure/Doctrine/Repository/DoctrineNotificationRepository.php`
- `api/src/Notification/Infrastructure/Doctrine/Repository/DoctrineNotificationReadRepository.php`
- `api/src/Admin/Infrastructure/HttpController/Crud/NotificationCrudController.php`

**Fichiers modifiés :**
- `api/config/packages/doctrine.php` — domaine `Notification` enregistré
- `api/src/Admin/Infrastructure/HttpController/DashboardController.php` — menu "Notifications" ajouté
- `api/translations/admin.fr.yaml` — traductions FR du CRUD
- `api/config/database/migrations/Version20260402140100.php` — migration appliquée (tables `notification`, `notification_targets`, `notification_read`)

**Pièges rencontrés à ne pas reproduire :**
- `DoctrineRepository` (classe parente des repos du projet) n'étend PAS `EntityRepository` → `AssociationField` d'EasyAdmin plante si on le laisse chercher le repo seul. Fix : passer un `QueryBuilder` via `$this->em->createQueryBuilder()` dans `setFormTypeOption('query_builder', ...)`
- `ChoiceField` avec un enum PHP : besoin d'un callback `choice_value` pour l'affichage ET le setter doit accepter `NotificationTypeEnum|string` avec `NotificationTypeEnum::from($value)` pour la soumission du formulaire
- Tout nouveau domaine DDD doit être déclaré dans `api/config/packages/doctrine.php` sous `orm.mappings`, sinon Doctrine ignore les entités

---

### BLOC 2 — App joueur (page Notifications + rond rouge) ✅ TERMINÉ (03/04/2026)

**Fichiers créés :**
- `api/src/Notification/Application/Query/GetNotificationsForUser/` — Query + Handler
- `api/src/Notification/Application/Command/MarkNotificationRead/` — Command + Handler
- `api/src/Notification/Application/Command/MarkAllNotificationsRead/` — Command + Handler
- `api/src/Notification/Application/Command/DeleteNotification/` — Command + Handler
- `api/src/Notification/Infrastructure/ApiPlatform/Resource/NotificationResource.php`
- `api/src/Notification/Infrastructure/ApiPlatform/State/Provider/NotificationCollectionProvider.php`
- `api/src/Notification/Infrastructure/ApiPlatform/State/Provider/NotificationItemProvider.php`
- `api/src/Notification/Infrastructure/ApiPlatform/State/Processor/MarkNotificationReadProcessor.php`
- `api/src/Notification/Infrastructure/ApiPlatform/State/Processor/MarkAllNotificationsReadProcessor.php`
- `api/src/Notification/Infrastructure/ApiPlatform/State/Processor/DeleteNotificationProcessor.php`
- `app/actions/notifications/markNotificationRead-action.ts`
- `app/actions/notifications/markAllNotificationsRead-action.ts`
- `app/actions/notifications/deleteNotification-action.ts`
- `app/app/(logged)/profil/notifications/page.tsx`
- `app/app/(logged)/profil/notifications/_components/NotificationItem.tsx`
- `app/app/(logged)/profil/notifications/_components/MarkAllReadButton.tsx`
- `app/components/custom/notification-badge.tsx`

**Fichiers modifiés :**
- `app/app/_components/Header.tsx` — fetch notifs + NotificationBadge sur l'icône profil
- `app/app/(logged)/profil/page.tsx` — fetch notifs + badge sur le LinkBlock "Notifications"
- `app/components/custom/linkBlock.tsx` — prop `badge?: boolean` ajouté

**Pièges résolus :**
- `h-2.5`/`w-2.5` inexistants dans le Tailwind custom (spacing = entiers uniquement) → utiliser `h-[10px] w-[10px]`
- Badge positionné avec `-top-[5px] -right-[5px]` pour déborder à cheval sur l'icône

---

### BLOC 3 — Popup + liens interactifs ✅ TERMINÉ (03/04/2026)

**Fichiers créés :**
- `api/src/Notification/Application/Query/GetPopupNotificationForUser/` — Query + Handler
- `api/src/Notification/Infrastructure/ApiPlatform/State/Provider/NotificationPopupProvider.php`
- `api/src/Shared/Infrastructure/Console/Notification/PublishScheduledNotificationsCommand.php`
- `app/components/custom/notification-popup.tsx` — modal client-side
- `api/config/database/migrations/Version20260403120000.php` — colonne `show_as_popup`

**Fichiers modifiés :**
- `api/src/Notification/Infrastructure/Doctrine/Entity/Notification.php` — champ `showAsPopup`
- `api/src/Notification/Infrastructure/Doctrine/Repository/DoctrineNotificationRepository.php` — méthode `findPopupForUser`
- `api/src/Notification/Infrastructure/ApiPlatform/Resource/NotificationResource.php` — opération `GET /notifications/popup` en première position (piège : conflit de route avec `/{uuid}`)
- `api/src/Admin/Infrastructure/HttpController/Crud/NotificationCrudController.php` — champ "Afficher en popup ?"
- `api/translations/admin.fr.yaml` — traduction `show_as_popup`
- `app/app/(hub)/layout.tsx` — fetch popup + rendu `NotificationPopup`
- `app/app/(logged)/profil/notifications/_components/NotificationItem.tsx` — classe `notification-body`
- `app/styles/_components-custom.css` — `.notification-body a` (doré souligné) + `word-break: break-word`
- `Makefile` — targets `api-publish-notifications` + `api-notifications-worker`

**Comportement popup :**
- La plus ancienne popup non lue s'affiche en premier (ordre ASC)
- Fermer la popup = notif marquée comme lue = ne se réaffiche plus
- Lire la notif depuis la liste = même effet, elle disparaît de la file des popups
- Notifications programmées : fenêtre de tolérance 10 minutes (si le serveur était coupé plus longtemps, la notif est ignorée)

**Piège résolu :**
- L'opération `GET /notifications/popup` doit être déclarée EN PREMIER dans le tableau `operations[]` du `NotificationResource`. Sinon la route `GET /notifications/{uuid}` générée automatiquement par API Platform l'intercepte.

**En production :**
```
* * * * * php /var/www/kop/bin/console kop:notifications:publish-scheduled
```
À ajouter au crontab serveur par le freelance.

---

## FEATURE TERMINÉE — Attribution de crédits par l'admin (EasyAdmin) ✅ (03/04/2026)

### Objectif
Permettre à un super-admin d'attribuer ou déduire des crédits manuellement depuis EasyAdmin, avec ciblage (tous les joueurs / un joueur / joueurs d'un championnat), motif visible par le joueur et notification automatique.

### Fichiers créés

**Backend :**
- `api/src/CreditWallet/Domain/Enum/GrantTargetType.php` — enum string (ALL, PLAYER, CHAMPIONSHIP) avec `choices()` pour EasyAdmin
- `api/src/CreditWallet/Infrastructure/Doctrine/Entity/AdminCreditGrant.php` — entité avec fields : uuid, amount, isDeduction, reason, targetType, targetPlayer (FK nullable), targetChampionship (FK nullable), excludedPlayers (ManyToMany), executedAt, createdAt
- `api/src/CreditWallet/Application/Command/ExecuteCreditGrant/ExecuteCreditGrantCommand.php`
- `api/src/CreditWallet/Application/Command/ExecuteCreditGrant/ExecuteCreditGrantCommandHandler.php` — résout les cibles, applique transactions, crée notifs système, crée le wallet à la volée si absent
- `api/src/Admin/Infrastructure/HttpController/Crud/AdminCreditGrantCrudController.php` — formulaire EasyAdmin avec champs conditionnels (visibilité JS) et boutons renommés
- `api/src/Admin/Infrastructure/HttpController/ChampionshipPlayersController.php` — route `GET /admin/api/championship-by-id/{id}/players` pour charger les joueurs d'un championnat en AJAX
- `api/config/database/migrations/Version20260403140000.php` — tables `admin_credit_grant` + `admin_credit_grant_excluded_players` (appliquée)

**Fichiers modifiés :**
- `api/src/CreditWallet/Domain/Enum/TransactionType.php` — ajout `ADMIN_GRANT` + `ADMIN_DEDUCTION`
- `api/src/CreditWallet/Domain/Enum/TransactionOperator.php` — match étendu aux nouveaux types
- `api/src/Admin/Infrastructure/HttpController/DashboardController.php` — menu "Attributions de crédits" ajouté
- `api/translations/admin.fr.yaml` — section `credit_grant` + `notification` ajoutées
- `app/app/(logged)/portefeuille/page.tsx` — fix crash null wallet (`?.credit ?? 0`) + `cache: 'no-store'` sur les fetches

### Pièges résolus (à ne pas reproduire)

**EasyAdmin 4 n'appelle pas `form_row()` de Symfony** dans ses templates → `row_attr` et `addCssClass()` n'atterrissent jamais dans le DOM rendu. Pour les champs conditionnels, la seule approche fiable : poser `data-grant-field` directement sur l'`<input>`/`<select>` via `setFormTypeOption('attr', ...)`, puis en JS remonter le DOM avec une boucle jusqu'à un ancêtre `col-*` ou `form-group` pour le masquer/afficher.

**`DoctrineRepository::findAll()` lève `LogicException('Not implemented')`** — toujours utiliser une DQL query directe : `$this->em->createQuery('SELECT u FROM ' . UserVisitor::class . ' u')->getResult()`.

**Comptes créés directement en SQL** (test, admin) n'ont pas de `CreditWallet` associé. Le handler le crée à la volée quand il est null, plutôt que de silencieusement skiper l'utilisateur.

**Next.js 14 App Router cache les `fetch()` server-side par défaut.** Ajouter `cache: 'no-store'` sur tout fetch qui doit refléter des données fraîches (solde, notifications, etc.).

---

## À FAIRE EN DÉBUT DE PROCHAINE SESSION — Commit de sauvegarde

Commencer la session par un commit git `perso` pour sauvegarder l'état actuel de toutes les features terminées :

```bash
git add -A
git commit -m "feat: notifications in-app + attribution crédits admin"
git push perso main
```

Fichiers à inclure dans ce commit (toutes les features depuis le dernier commit) :
- Centre de notifications (Bloc 1, 2, 3) — voir section BLOC 1/2/3 ci-dessus
- Attribution de crédits admin — voir section ci-dessus
- Fix portefeuille page crash

