#!/usr/bin/env bash
set -euo pipefail

# ─────────────────────────────────────────────────────────────────────────────
# App Deploy Script — Staging
# Deploys Next.js app via git pull + Docker Compose rebuild on the server.
#
# Required env vars: SSH_USER, SSH_HOST, APP_PATH, DEPLOY_REF
# SSH config alias "staging" must be configured before running this script.
# ─────────────────────────────────────────────────────────────────────────────

SSH_TARGET="${SSH_USER}@${SSH_HOST}"

echo "==> Deploying App to ${SSH_TARGET}:${APP_PATH}"
echo "    Ref: ${DEPLOY_REF}"

# ─── Step 1: Pull latest code ───────────────────────────────────────────
echo ""
echo "--- Step 1/4: Pull latest code"
# Variables are expanded locally (intentional: APP_PATH and DEPLOY_REF come from CI env)
ssh staging << EOF
  cd ${APP_PATH}
  git fetch --all --prune
  git checkout ${DEPLOY_REF}
  git pull origin ${DEPLOY_REF} || true
EOF

# ─── Step 2: Build Docker image ─────────────────────────────────────────
echo ""
echo "--- Step 2/4: Build Docker image"
ssh staging "cd ${APP_PATH} && docker compose -f app/docker-compose.staging.yml build --no-cache"

# ─── Step 3: Restart containers ─────────────────────────────────────────
echo ""
echo "--- Step 3/4: Restart containers"
ssh staging "cd ${APP_PATH} && docker compose -f app/docker-compose.staging.yml down && docker compose -f app/docker-compose.staging.yml up -d"

# ─── Step 4: Verify deployment ──────────────────────────────────────────
echo ""
echo "--- Step 4/4: Verify deployment"
# Quoted heredoc: variables are NOT expanded locally (intentional: runs entirely on server)
ssh staging << 'VERIFY'
  sleep 5
  if docker ps --filter "name=staging-app-kingofpaddock" --filter "status=running" | grep -q staging-app-kingofpaddock; then
    echo "Container staging-app-kingofpaddock is running"
  else
    echo "ERROR: Container staging-app-kingofpaddock is NOT running"
    docker logs staging-app-kingofpaddock --tail 30 2>&1 || true
    exit 1
  fi
VERIFY

echo ""
echo "==> App deployment complete!"
