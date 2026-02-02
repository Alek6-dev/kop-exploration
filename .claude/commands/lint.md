---
name: lint
description: Run linters on both API (PHP) and App (TypeScript)
---

Run all code quality checks across the monorepo.

## Execute these commands:

### 1. PHP (API)
```bash
cd api && make cs-fix
```
This runs PHP-CS-Fixer to fix code style issues.

### 2. TypeScript/JavaScript (App)
```bash
pnpm --filter @kop/app lint
```
This runs ESLint on the Next.js application.

### 3. Optional: Type checking
```bash
pnpm --filter @kop/app type-check
```
If available, run TypeScript type checking.

## Report:
- List any files that were modified by the linters
- Report any errors that couldn't be auto-fixed
- Suggest manual fixes if needed
