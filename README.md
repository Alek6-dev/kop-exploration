# King of Paddock

A motorsport fantasy league application where players manage virtual racing teams, compete in driver auctions, and earn points based on real race results.

## Overview

King of Paddock is a monorepo containing:

| Project | Description | Tech Stack |
|---------|-------------|------------|
| **api** | Backend API and admin panel | Symfony 6.4, PHP 8.3, API Platform, MySQL, Elasticsearch |
| **app** | Frontend web application (PWA) | Next.js 14, TypeScript, Tailwind CSS, shadcn/ui |

## Prerequisites

- **Node.js** 20+ (use [nvm](https://github.com/nvm-sh/nvm): `nvm use`)
- **pnpm** 9+ (`npm install -g pnpm`)
- **Docker** and **Docker Compose**

## Quick Start

```bash
# Clone the repository
git clone <repository-url> kop
cd kop

# Install all dependencies
make install

# Start all services
make up

# Set up the database (first time only)
make api-db
```

Access the applications:
- **API**: https://kop.local (requires hosts file configuration)
- **Admin Panel**: https://kop.local/admin
- **Frontend App**: http://localhost:3000

## Available Commands

Run `make help` for a full list of commands.

### Docker Management

| Command | Description |
|---------|-------------|
| `make up` | Start all Docker services (API + App) |
| `make down` | Stop all Docker services |
| `make restart` | Restart all Docker services |
| `make api-up` | Start API services only |
| `make api-down` | Stop API services only |
| `make app-up` | Start App services only |
| `make app-down` | Stop App services only |

### Development

| Command | Description |
|---------|-------------|
| `make install` | Install all dependencies (pnpm + composer) |
| `make build` | Build all projects |
| `make api-db` | Reset API database with fixtures |
| `make api-test` | Run API tests (PestPHP) |
| `make app-dev` | Start App in local dev mode |
| `make app-lint` | Lint App code |
| `make clean` | Remove all node_modules and build artifacts |

### pnpm Workspace Commands

```bash
pnpm install                     # Install all dependencies
pnpm --filter @kop/api build     # Build API assets
pnpm --filter @kop/app dev       # Start App dev server
pnpm --filter @kop/app build     # Build App for production
pnpm -r run build                # Build all projects
```

## Project Structure

```
kop/
├── api/                 # Symfony backend
│   ├── src/            # PHP source (DDD architecture)
│   ├── config/         # Configuration + Docker
│   ├── Makefile        # API commands
│   └── docker-compose.yml
│
├── app/                 # Next.js frontend
│   ├── app/            # Next.js App Router
│   ├── components/     # React components
│   ├── Makefile        # App commands
│   └── docker-compose.yml
│
├── Makefile            # Root commands
├── pnpm-workspace.yaml # Workspace config
├── package.json        # Root package
└── README.md           # This file
```

## Architecture

### Backend (API)

The API follows **Domain-Driven Design (DDD)** principles with **CQRS** pattern:
- Feature-based module organization (`Championship`, `Driver`, `Race`, etc.)
- Commands for write operations, Queries for read operations
- API Platform for REST API generation
- EasyAdmin for the back-office interface

### Frontend (App)

The frontend is built with Next.js 14 using the App Router:
- TypeScript for type safety
- Tailwind CSS for styling
- shadcn/ui components (built on Radix UI)
- react-hook-form with Zod validation
- Server Actions for mutations

## Documentation

- [API Documentation](./api/README.md) - Detailed backend setup and development guide
- [App Documentation](./app/README.md) - Frontend development guide

## Contributing

1. Create a feature branch from `main`
2. Follow [Conventional Commits](https://www.conventionalcommits.org/) for commit messages
3. Run tests before submitting: `make api-test`
4. Submit a pull request

## License

Proprietary - All rights reserved.
