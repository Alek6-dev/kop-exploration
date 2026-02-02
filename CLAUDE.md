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
