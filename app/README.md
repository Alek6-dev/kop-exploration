# King of Paddock - Frontend

The frontend application for **King of Paddock (KOP)**, a motorsport fantasy league platform where users manage virtual racing teams, participate in auctions, and compete based on real race results.

## Tech Stack

- **Framework**: [Next.js 14](https://nextjs.org/) with App Router
- **Language**: [TypeScript](https://www.typescriptlang.org/) (strict mode)
- **Styling**: [Tailwind CSS](https://tailwindcss.com/)
- **UI Components**: [shadcn/ui](https://ui.shadcn.com/) (Radix UI primitives)
- **Forms**: [react-hook-form](https://react-hook-form.com/) with [Zod](https://zod.dev/) validation
- **Icons**: [Lucide React](https://lucide.dev/)
- **Package Manager**: [pnpm](https://pnpm.io/) (monorepo workspace)

## Prerequisites

- **Node.js 20+** (use `nvm use` to automatically switch to the correct version)
- **pnpm 9+** (install via `npm install -g pnpm`)
- **Docker & Docker Compose** (for containerized development)
- **API Backend** running (see `/api` directory)

## Project Structure

```
app/
├── app/                   # Next.js App Router pages and layouts
│   ├── (guest)/           # Public routes (login, register, etc.)
│   ├── (logged)/          # Protected routes (dashboard, teams, etc.)
│   ├── (hub)/             # Championship hub routes
│   ├── (legals)/          # Legal pages (terms, privacy, etc.)
│   ├── _components/       # Shared app-level components
│   ├── layout.tsx         # Root layout
│   └── not-found.tsx      # 404 page
│
├── actions/               # Server Actions organized by domain
│   ├── championship/      # Championship-related actions
│   ├── paddock/           # Paddock/team management actions
│   ├── profile/           # User profile actions
│   ├── security/          # Authentication actions
│   └── wallet/            # Credit wallet actions
│
├── components/            # React components
│   ├── ui/                # shadcn/ui components (auto-generated)
│   └── custom/            # Custom application components
│
├── constants/             # Application constants and configuration
├── hooks/                 # Custom React hooks
├── lib/                   # Utility libraries and helpers
├── messages/              # Internationalization messages
├── public/                # Static assets (images, icons, etc.)
├── styles/                # Global styles and CSS modules
├── type/                  # TypeScript type definitions
└── utils/                 # Utility functions
```

## Getting Started

### Installation

From the **monorepo root**:

```bash
# Install all dependencies
make install

# Or install app dependencies only
make app-install
```

From the **app directory**:

```bash
# Install dependencies using pnpm
pnpm install
```

### Development

#### Option 1: Using Make Commands (Recommended)

From the **monorepo root**:

```bash
# Start app in development mode (local, no Docker)
make app-dev

# Or start with Docker
make app-up
```

#### Option 2: Direct pnpm Commands

From the **app directory**:

```bash
# Start development server with HTTPS
pnpm dev

# The app will be available at:
# http://localhost:3000
```

#### Option 3: Docker

From the **app directory**:

```bash
# Using Makefile
make docker-up

# Or using Docker Compose directly
docker compose up -d
```

### Building for Production

```bash
# From monorepo root
make app-build

# Or from app directory
pnpm build

# Start production server
pnpm start
```

## Available Scripts

From the **app directory**:

| Command      | Description                          |
|--------------|--------------------------------------|
| `pnpm dev`   | Start development server with HTTPS  |
| `pnpm build` | Build the application for production |
| `pnpm start` | Start the production server          |
| `pnpm lint`  | Run ESLint to check code quality     |

From the **monorepo root**:

| Command            | Description                              |
|--------------------|------------------------------------------|
| `make app-dev`     | Start app development server (no Docker) |
| `make app-up`      | Start app with Docker                    |
| `make app-down`    | Stop app Docker services                 |
| `make app-build`   | Build app for production                 |
| `make app-lint`    | Run linter                               |
| `make app-restart` | Restart Docker services                  |

## Architecture & Patterns

### App Router Structure

The application uses Next.js 14 App Router with route groups for organization:

- **(guest)**: Unauthenticated routes (login, register, password reset)
- **(logged)**: Protected routes requiring authentication
- **(hub)**: Championship-specific pages
- **(legals)**: Legal and informational pages

### Server Components & Server Actions

The app leverages Next.js Server Components and Server Actions for optimal performance:

- **Server Components**: Default for all components, reducing JavaScript bundle size
- **Server Actions**: Located in `/actions`, organized by domain (championship, paddock, profile, security, wallet)
- **Client Components**: Used only when interactivity requires client-side JavaScript (marked with `"use client"`)

### Routing Conventions

- `page.tsx`: Route page component
- `layout.tsx`: Shared layout for nested routes
- `loading.tsx`: Loading UI (automatic with Suspense)
- `error.tsx`: Error boundary
- `not-found.tsx`: 404 page

### Component Organization

#### UI Components (shadcn/ui)

Located in `/components/ui/`, these are auto-generated components from shadcn/ui:

```bash
# Add new shadcn/ui components
npx shadcn-ui@latest add button
npx shadcn-ui@latest add form
npx shadcn-ui@latest add dialog
```

Configuration is in `components.json`.

#### Custom Components

Located in `/components/custom/`, these are application-specific components built on top of shadcn/ui primitives.

### Form Handling

Forms use react-hook-form with Zod validation:

```typescript
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import * as z from "zod";

const formSchema = z.object({
  email: z.string().email(),
  password: z.string().min(8),
});

const form = useForm({
  resolver: zodResolver(formSchema),
  defaultValues: { email: "", password: "" },
});
```

### TypeScript Configuration

- **Strict mode enabled**: Enforces type safety
- **Path aliases**: `@/*` maps to project root for clean imports
- **ES6 target**: Modern JavaScript features

## Styling Guidelines

### Tailwind CSS

The project uses Tailwind CSS for styling with custom configuration:

- **CSS Variables**: Theme colors defined in CSS variables for easy customization
- **Base Color**: Slate
- **Animations**: `tailwindcss-animate` for component animations

### Best Practices

1. **Use Tailwind utility classes** for styling
2. **Follow shadcn/ui patterns** for component styling
3. **Use CSS variables** for theme colors (defined in `app/globals.css`)
4. **Responsive design**: Mobile-first approach with Tailwind breakpoints

Example:

```tsx
<div className="flex flex-col gap-4 md:flex-row md:gap-6">
  <Button variant="default" size="lg">
    Primary Action
  </Button>
</div>
```

## Code Conventions

### TypeScript

- **Strict mode enabled**: All types must be explicit
- **Interface over type**: Use `interface` for object shapes
- **Avoid `any`**: Use proper typing or `unknown` when type is uncertain

### File Naming

- **Components**: PascalCase (e.g., `UserProfile.tsx`)
- **Utilities**: camelCase (e.g., `formatDate.ts`)
- **Server Actions**: camelCase (e.g., `loginUser.ts`)

### Import Order

1. React & Next.js imports
2. Third-party libraries
3. Internal components
4. Utils & types
5. Styles

### Component Structure

```tsx
"use client"; // Only if needed

import { useState } from "react";
import { Button } from "@/components/ui/button";

interface ComponentProps {
  title: string;
  onSubmit: () => void;
}

export function Component({ title, onSubmit }: ComponentProps) {
  const [isLoading, setIsLoading] = useState(false);

  return (
    <div className="flex flex-col gap-4">
      <h1>{title}</h1>
      <Button onClick={onSubmit} disabled={isLoading}>
        Submit
      </Button>
    </div>
  );
}
```

## Environment Variables

Create a `.env` file in the app directory:

```env
# API Configuration
NEXT_PUBLIC_API_URL=https://api.kingofpaddock.com

# Add other environment variables as needed
```

## Integration with Backend

The app communicates with the Symfony backend API (located in `/api`):

- **API Base URL**: Configured via environment variables
- **Authentication**: JWT tokens stored in cookies
- **Server Actions**: Handle API calls server-side for security

## Docker Support

The app includes Docker support for containerized development:

- **Development**: Hot reload enabled
- **Production**: Optimized build with minimal image size

See `docker-compose.yml` and `Dockerfile` for configuration details.

## SVG Handling

SVGs are handled as React components using `@svgr/webpack`:

```tsx
import Logo from "@/public/logo.svg";

<Logo className="w-24 h-24" />
```

## Image Optimization

Custom image loader configured for integration with the backend API:

```tsx
import Image from "next/image";

<Image
  src="/uploads/image.jpg"
  alt="Description"
  width={400}
  height={300}
/>
```

## Linting & Code Quality

- **ESLint**: Configured with `next/core-web-vitals` rules
- **TypeScript**: Strict mode for type safety

Run linting:

```bash
# From app directory
pnpm lint

# From monorepo root
make app-lint
```

## Common Issues & Troubleshooting

### Port Already in Use

If port 3000 is already in use:

```bash
# Kill the process using port 3000
lsof -ti:3000 | xargs kill -9

# Or change the port
pnpm dev -- -p 3001
```

### HTTPS Certificate Errors

The dev server runs with `--experimental-https`. If you encounter certificate errors, accept the self-signed certificate in your browser.

### Module Not Found

If you encounter module resolution errors:

```bash
# Clear Next.js cache
rm -rf .next

# Reinstall dependencies
rm -rf node_modules
pnpm install
```

## Useful Resources

- [Next.js Documentation](https://nextjs.org/docs) - Learn about Next.js features and API
- [shadcn/ui Components](https://ui.shadcn.com/docs/components) - UI component library
- [Tailwind CSS](https://tailwindcss.com/docs) - Utility-first CSS framework
- [React Hook Form](https://react-hook-form.com/) - Form validation library
- [Zod Documentation](https://zod.dev/) - TypeScript-first schema validation
- [Radix UI](https://www.radix-ui.com/) - Unstyled, accessible components

## Contributing

When contributing to this project:

1. **Follow TypeScript strict mode** - No `any` types
2. **Use conventional commits** - Format: `type(scope): message`
3. **Test your changes** - Ensure the app builds and runs without errors
4. **Follow existing patterns** - Match the architecture and code style
5. **Update documentation** - Keep README and comments up to date

## License

This project is part of the King of Paddock application. See the root repository for license information.
