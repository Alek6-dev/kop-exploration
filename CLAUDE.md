# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

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

### Comptes de test (chargés via fixtures)

- **Admin** : `admin+super@kop.fr` / `password`
- **Joueurs** : emails aléatoires générés par Faker, mot de passe `password` pour tous

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

Ouvrir deux terminaux :

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
- **Session 2 — Backend Symfony en détail** : domaines métier (DDD), comment fonctionne une requête, CQRS, entités
- **Session 3 — Frontend Next.js en détail** : pages, composants, Server Actions, comment les données arrivent
- **Session 4 — Infra** : Docker, déploiement, environnements (local/staging/prod)

### Règles de collaboration

- Tout le code produit ici est expérimental, jamais déployé sans review du freelance
- S'adresser au propriétaire comme à un développeur junior : expliquer les termes, proposer par petites étapes
- Zones à ne pas toucher sans le freelance : auth (JWT), Stripe, Firewall/Security, migrations de base de données
- Zones safe pour expérimenter : frontend (pages, composants, styles), nouvelles pages, améliorations UX

### Chantiers identifiés (audit EVALUATE.md)

Par ordre de priorité :
1. **Cache Redis** côté backend — réduire la latence
2. **Déploiement** — moderniser le pipeline hérité de l'agence
3. **Rechargements de page** — remplacer `window.location.reload()` par `router.refresh()`
4. **PWA / notifications push** — réengager les joueurs
5. **App native (stores)** — via Capacitor (wrapper de l'app Next.js existante), option la plus économique
