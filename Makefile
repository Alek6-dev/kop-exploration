# King of Paddock - Monorepo Makefile
# ===================================

# Platform detection (following API Makefile pattern)
UNAME_S := $(shell uname -s)
ifeq ($(UNAME_S),Linux)
DOCKER_COMPOSE = docker-compose
else ifeq ($(UNAME_S),Darwin)
DOCKER_COMPOSE = docker compose
endif

# Directories
API_DIR = api
APP_DIR = app

# Colors for output
CYAN = \033[36m
GREEN = \033[32m
YELLOW = \033[33m
RESET = \033[0m

.SILENT:
.PHONY: help install build dev up down restart logs \
        api-up api-down api-restart api-build api-install api-setup api-db api-test \
        app-up app-down app-restart app-build app-install app-dev app-lint \
        clean

# ============================================================================
# HELP
# ============================================================================

help: ## Display this help message
	@echo "$(CYAN)King of Paddock - Monorepo Commands$(RESET)"
	@echo ""
	@grep -hE '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  $(CYAN)%-20s$(RESET) %s\n", $$1, $$2}'

# ============================================================================
# GLOBAL COMMANDS
# ============================================================================

install: ## Install all dependencies (pnpm for Node, composer for PHP)
	@echo "$(CYAN)--> Installing all dependencies$(RESET)"
	@pnpm install
	@$(MAKE) -C $(API_DIR) composer-install

build: ## Build all projects
	@echo "$(CYAN)--> Building all projects$(RESET)"
	@pnpm run build

dev: ## Start development mode for all projects
	@echo "$(CYAN)--> Starting development mode$(RESET)"
	@pnpm run dev

# ============================================================================
# DOCKER - ALL SERVICES
# ============================================================================

up: ## Start all Docker services (API + App)
	@echo "$(CYAN)--> Starting all Docker services$(RESET)"
	@$(MAKE) api-up
	@$(MAKE) app-up

down: ## Stop all Docker services
	@echo "$(CYAN)--> Stopping all Docker services$(RESET)"
	@$(MAKE) api-down
	@$(MAKE) app-down

restart: down up ## Restart all Docker services

logs: ## View logs from all services (API)
	@echo "$(CYAN)--> Viewing logs$(RESET)"
	@cd $(API_DIR) && $(DOCKER_COMPOSE) logs -f

# ============================================================================
# API COMMANDS
# ============================================================================

api-up: ## Start API Docker services only
	@echo "$(CYAN)--> Starting API services$(RESET)"
	@$(MAKE) -C $(API_DIR) docker-up

api-down: ## Stop API Docker services only
	@echo "$(CYAN)--> Stopping API services$(RESET)"
	@$(MAKE) -C $(API_DIR) docker-down

api-restart: api-down api-up ## Restart API services

api-build: ## Build API assets
	@echo "$(CYAN)--> Building API assets$(RESET)"
	@$(MAKE) -C $(API_DIR) npm-build

api-install: ## Install API dependencies (composer + npm)
	@echo "$(CYAN)--> Installing API dependencies$(RESET)"
	@$(MAKE) -C $(API_DIR) composer-install
	@pnpm --filter @kop/api install

api-setup: api-up ## Full API setup (docker + wait for MySQL)
	@echo "$(CYAN)--> Waiting for MySQL...$(RESET)"
	@$(MAKE) -C $(API_DIR) wait-for-mysql
	@echo "$(GREEN)--> API setup complete$(RESET)"

api-db: ## Setup API database with fixtures
	@echo "$(CYAN)--> Setting up API database$(RESET)"
	@$(MAKE) -C $(API_DIR) db-dev

api-test: ## Run API tests
	@echo "$(CYAN)--> Running API tests$(RESET)"
	@$(MAKE) -C $(API_DIR) pest-run

# ============================================================================
# APP COMMANDS
# ============================================================================

app-up: ## Start App Docker services only
	@echo "$(CYAN)--> Starting App services$(RESET)"
	@$(MAKE) -C $(APP_DIR) docker-up

app-down: ## Stop App Docker services only
	@echo "$(CYAN)--> Stopping App services$(RESET)"
	@$(MAKE) -C $(APP_DIR) docker-down

app-restart: app-down app-up ## Restart App services

app-build: ## Build App for production
	@echo "$(CYAN)--> Building App$(RESET)"
	@pnpm --filter @kop/app run build

app-install: ## Install App dependencies
	@echo "$(CYAN)--> Installing App dependencies$(RESET)"
	@pnpm --filter @kop/app install

app-dev: ## Start App in development mode (local, no Docker)
	@echo "$(CYAN)--> Starting App development server$(RESET)"
	@pnpm --filter @kop/app run dev

app-lint: ## Lint App code
	@echo "$(CYAN)--> Linting App$(RESET)"
	@pnpm --filter @kop/app run lint

# ============================================================================
# CLEAN
# ============================================================================

clean: ## Remove all node_modules and build artifacts
	@echo "$(YELLOW)--> Cleaning all build artifacts$(RESET)"
	@rm -rf node_modules
	@rm -rf $(API_DIR)/node_modules
	@rm -rf $(APP_DIR)/node_modules
	@rm -rf $(APP_DIR)/.next
	@rm -rf $(API_DIR)/public/assets/bundle
	@echo "$(GREEN)--> Clean complete$(RESET)"
