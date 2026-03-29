FROM node:20-alpine

RUN apk add --no-cache libc6-compat tzdata
ENV TZ=Europe/Paris

# Enable pnpm via corepack
RUN corepack enable && corepack prepare pnpm@9.15.0 --activate

WORKDIR /app

# Copy dependency files first (Docker layer caching)
COPY package.json pnpm-lock.yaml pnpm-workspace.yaml ./
COPY app/package.json ./app/

# Install only app dependencies
RUN pnpm install --frozen-lockfile --filter @kop/app

# Copy app source and build
COPY app/ ./app/
WORKDIR /app/app
RUN pnpm run build

ENV NEXT_TELEMETRY_DISABLED=1
EXPOSE 3000
ENV PORT=3000
ENV HOSTNAME="0.0.0.0"

CMD ["pnpm", "start"]
