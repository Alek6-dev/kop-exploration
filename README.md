# King of Paddock

A motorsport fantasy league application where players manage virtual racing teams, compete in driver auctions, and earn points based on real race results.

## Overview

King of Paddock is a monorepo containing two main applications:

| Project | Description                    | Tech Stack                                               |
|---------|--------------------------------|----------------------------------------------------------|
| **api** | Backend API and admin panel    | Symfony 6.4, PHP 8.3, API Platform, MySQL, Elasticsearch |
| **app** | Frontend web application (PWA) | Next.js 14, TypeScript, Tailwind CSS, shadcn/ui          |

## Prerequisites

Before you begin, ensure you have the following installed:

- **Node.js** 20+ (use [nvm](https://github.com/nvm-sh/nvm): `nvm use`)
- **pnpm** 9+ (`npm install -g pnpm`)
- **Docker** and **Docker Compose**
- **PHP 8.3** (optional, for local non-Docker development)

## Quick Start

### 1. Clone and Install

```bash
# Clone the repository
git clone <repository-url> kop
cd kop

# Install all dependencies
make install
```

### 2. Configure Hosts File

To access the API at `https://kop.local`, add this entry to your hosts file:

```bash
# On macOS/Linux: /etc/hosts
# On Windows: C:\Windows\System32\drivers\etc\hosts
127.0.0.1 kop.local
```

### 3. Start Services

```bash
# Start all Docker services (API + App)
make up

# Set up the database with fixtures (first time only)
make api-db
```

### 4. Access Applications

- **API**: https://kop.local
- **Admin Panel**: https://kop.local/admin
- **Frontend App**: http://localhost:3000

## Available Commands

Run `make help` to see all available commands.

### Global Commands

| Command        | Description                                 |
|----------------|---------------------------------------------|
| `make install` | Install all dependencies (pnpm + composer)  |
| `make build`   | Build all projects                          |
| `make dev`     | Start development mode for all projects     |
| `make up`      | Start all Docker services (API + App)       |
| `make down`    | Stop all Docker services                    |
| `make restart` | Restart all Docker services                 |
| `make clean`   | Remove all node_modules and build artifacts |

### API Commands

| Command            | Description                              |
|--------------------|------------------------------------------|
| `make api-up`      | Start API Docker services only           |
| `make api-down`    | Stop API Docker services only            |
| `make api-restart` | Restart API services                     |
| `make api-db`      | Reset database with fixtures             |
| `make api-test`    | Run API tests (PestPHP)                  |
| `make api-build`   | Build API assets                         |
| `make api-setup`   | Full API setup (Docker + wait for MySQL) |

### App Commands

| Command            | Description                             |
|--------------------|-----------------------------------------|
| `make app-up`      | Start App Docker services only          |
| `make app-down`    | Stop App Docker services only           |
| `make app-restart` | Restart App services                    |
| `make app-dev`     | Start App in local dev mode (no Docker) |
| `make app-build`   | Build App for production                |
| `make app-lint`    | Lint App code                           |

### Using pnpm Workspaces

The project uses pnpm workspaces for managing multiple packages:

```bash
# Install all dependencies
pnpm install

# Run commands in specific workspace
pnpm --filter @kop/api build      # Build API assets
pnpm --filter @kop/app dev        # Start App dev server
pnpm --filter @kop/app build      # Build App for production

# Run command in all workspaces
pnpm -r run build                 # Build all projects
```

## Project Structure

```
kop/
├── api/                   # Symfony backend
│   ├── src/               # PHP source (DDD architecture)
│   │   ├── Championship/  # Championship management
│   │   ├── Driver/        # Driver entities and logic
│   │   ├── Race/          # Race management
│   │   ├── Player/        # Player accounts
│   │   ├── Team/          # Team management
│   │   ├── Bid/           # Auction/bidding system
│   │   ├── CreditWallet/  # Virtual currency
│   │   └── Shared/        # Shared utilities
│   ├── config/            # Symfony configuration + Docker configs
│   ├── public/            # Web root + compiled assets
│   ├── templates/         # Twig templates (emails, admin)
│   ├── Makefile           # API-specific commands (60+ targets)
│   └── docker-compose.yml # Backend services (MySQL, PHP, Nginx, ES)
│
├── app/                   # Next.js frontend
│   ├── app/               # Next.js App Router pages
│   ├── components/        # React components
│   ├── actions/           # Server Actions
│   ├── Makefile           # App-specific commands
│   └── docker-compose.yml # Frontend container
│
├── Makefile               # Root-level orchestration commands
├── pnpm-workspace.yaml    # pnpm workspace configuration
├── package.json           # Root package with workspace scripts
└── README.md              # This file
```

## Architecture

### Backend (API)

The API follows **Domain-Driven Design (DDD)** principles with **CQRS** pattern:

- **Feature-based organization**: Each domain module (Championship, Driver, Race, etc.) contains its own entities, commands, queries, and business logic
- **Commands**: Handle write operations and business logic
- **Queries**: Handle read operations and data retrieval
- **API Platform**: Automatic REST API generation with OpenAPI documentation
- **EasyAdmin 4**: Back-office administration interface
- **Testing**: PestPHP for unit, integration, and architecture tests

**Key Technologies:**
- Symfony 6.4 with API Platform
- PHP 8.3
- MySQL for data persistence
- Elasticsearch for search functionality
- Stripe for payment processing

### Frontend (App)

The frontend is built with Next.js 14 using modern React patterns:

- **App Router**: Next.js 14's latest routing system
- **TypeScript**: Full type safety across the application
- **Tailwind CSS**: Utility-first CSS framework
- **shadcn/ui**: Beautiful, accessible UI components built on Radix UI
- **react-hook-form + Zod**: Form handling with schema validation
- **Server Actions**: Type-safe server mutations
- **PWA**: Progressive Web App capabilities for mobile

## Development Workflow

### Starting Development

```bash
# Start all services
make up

# Or start services individually
make api-up      # Backend only
make app-up      # Frontend only

# Reset database with fresh fixtures
make api-db

# Run tests
make api-test
```

### Code Style

The project follows these code style conventions:

- **PHP**: PHP-CS-Fixer (run `make cs-fix` in api/ directory)
- **TypeScript/JavaScript**: ESLint + Prettier
- **Commits**: [Conventional Commits](https://www.conventionalcommits.org/) format

### Testing

```bash
# Run all API tests
make api-test

# Run specific test groups (from api/ directory)
cd api
make pest-unit          # Unit tests only
make pest-integration   # Integration tests only
make pest-arch          # Architecture tests only
```

## Documentation

- [API Documentation](./api/README.md) - Detailed backend setup and development guide
- [App Documentation](./app/README.md) - Frontend development guide
- [CLAUDE.md](./CLAUDE.md) - AI assistant context and guidelines

## Contributing

1. Create a feature branch from `main`
2. Follow [Conventional Commits](https://www.conventionalcommits.org/) for commit messages
3. Write tests for new features
4. Run tests before submitting: `make api-test`
5. Ensure code style compliance
6. Submit a pull request

## Troubleshooting

### Hosts File Not Working

If `https://kop.local` doesn't resolve:
1. Verify the hosts file entry: `127.0.0.1 kop.local`
2. Flush DNS cache:
   - macOS: `sudo dscacheutil -flushcache; sudo killall -HUP mDNSResponder`
   - Windows: `ipconfig /flushdns`
   - Linux: `sudo systemd-resolve --flush-caches`

### Docker Services Won't Start

```bash
# Stop all services and remove containers
make down

# Clean Docker resources
docker system prune -a

# Restart services
make up
```

### Port Conflicts

If ports 80, 443, 3000, or 3306 are in use:
1. Stop conflicting services (Apache, Nginx, MySQL, etc.)
2. Or modify port mappings in `api/docker-compose.yml` and `app/docker-compose.yml`

### Database Issues

```bash
# Reset database completely
make api-db

# Or manually from api directory
cd api
make db-drop db-create db-migrate db-fixtures
```

## License

Proprietary - All rights reserved.
