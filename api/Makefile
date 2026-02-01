export USER_ID=`id -u`
export GROUP_ID=`id -g`

# include .env variables
-include .env
export $(shell sed 's/=.*//' .env)

# include .env.local variables
-include .env.local
export $(shell if [ -e .env.local ]; then sed 's/=.*//' .env.local; fi)

# include .env.$(APP_ENV), needed after the local one as it is the one on which the APP_ENV can be overridden
-include .env.$(APP_ENV)
export $(shell if [ -e .env.$(APP_ENV) ]; then sed 's/=.*//' .env.$(APP_ENV); fi)

UNAME_S := $(shell uname -s)
ifeq ($(UNAME_S),Linux)
# Configuration des alias Makefile pour **Linux**
DOCKEREXEC = USER_ID=${USER_ID} docker-compose exec $(DOCKEREXEC_ARG)
DOCKERPHP = ${DOCKEREXEC} php gosu kop php
DOCKERRUN = USER_ID=${USER_ID} GROUP_ID=${GROUP_ID} docker-compose run --rm
DOCKERCYPRESS = USER_ID=${USER_ID} GROUP_ID=${GROUP_ID} docker-compose -f docker-compose-cypress.yml run --rm
DOCKERCOMPO = USER_ID=${USER_ID} GROUP_ID=${GROUP_ID} docker-compose -p $(COMPOSE_PROJECT_NAME)
DOCKERCYPRESSRUNNER = USER_ID=${USER_ID} GROUP_ID=${GROUP_ID} docker-compose -f docker-compose-cypress-runner.yml run --rm
COMPOSE_PROFILE = linux
else ifeq ($(UNAME_S),Darwin)
# Configuration des alias Makefile pour **Apple** (utilisation de Docker pour PHP)
DOCKEREXEC = USER_ID=${USER_ID} docker compose exec $(DOCKEREXEC_ARG)
DOCKERPHP = ${DOCKEREXEC} php gosu kop php
DOCKERRUN = USER_ID=${USER_ID} GROUP_ID=${GROUP_ID} docker compose run --rm
DOCKERCOMPO = USER_ID=${USER_ID} GROUP_ID=${GROUP_ID} docker compose -p $(COMPOSE_PROJECT_NAME)
COMPOSE_PROFILE = macos
endif

DOCKERRUN_NODEPS = ${DOCKERRUN} --no-deps
DOCKERNODE = ${DOCKERRUN} --service-ports nodejs
DOCKERNPM = ${DOCKERNODE} npm
DOCKERPA11Y = ${DOCKERRUN} pa11y

# Help
.SILENT:
.PHONY: help

help: ## Display this help
	@grep -hE '^[A-Za-z0-9_ \-]*?:.*##.*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

# Git hooks
setup-hooks: ## setup local git hooks
	@echo "--> Setting up local git hooks"
	rsync -a -v --update ./config/git/ ./.git
	chmod +x .git/hooks/commit-msg
	chmod +x .git/hooks/pre-commit

# Docker
docker-build: ## build docker images
	@echo "--> Build docker images $(COMPOSE_PROJECT_NAME)"
	${DOCKERCOMPO} build

docker-up: ## start docker services
	@echo "--> Starting docker services $(COMPOSE_PROJECT_NAME)"
	${DOCKERCOMPO} --profile $(COMPOSE_PROFILE) up -d

docker-down: ## stop and remove docker services
	@echo "--> Stopping docker services $(COMPOSE_PROJECT_NAME)"
	${DOCKERCOMPO} down
docker-down-volumes:
	@echo "--> Stopping docker services $(COMPOSE_PROJECT_NAME)"
	${DOCKERCOMPO} down --volumes
docker-restart: docker-down docker-up

# Composer
composer-install: ## install composer packages (with dockerized composer)
	@echo "--> Installing composer packages"
	$(DOCKERPHP) -d memory_limit=-1 /usr/local/bin/composer install -vvv

composer-update: ## update composer packages (with dockerized composer)
	@echo "--> Installing composer packages"
	$(DOCKERPHP) -d memory_limit=-1 /usr/local/bin/composer update

composer-require: ## Require composer packages (with dockerized composer)
	@echo "--> Installing composer packages"
	$(DOCKERPHP) -d memory_limit=-1 /usr/local/bin/composer require ${ARGS}

composer-require-dev: ## Require composer packages (with dockerized composer)
	@echo "--> Installing composer packages"
	$(DOCKERPHP) -d memory_limit=-1 /usr/local/bin/composer require ${ARGS} --dev

composer-remove: ## Require composer packages (with dockerized composer)
	@echo "--> Remove composer packages"
	$(DOCKERPHP) -d memory_limit=-1 /usr/local/bin/composer remove ${ARGS}

# NPM
npm-develop: ## build assets with watcher for development
	@echo "--> Running npm develop command"
	${DOCKERNPM} run develop
npm-start: ## build assets with watcher for development
	@echo "--> Running npm start command"
	${DOCKERNPM} start
npm-build: ## build assets for production
	@echo "--> Build front-end dependencies"
	${DOCKERNPM} run build
npm-build-full: ## build assets for production
	@echo "--> Build front-end dependencies"
	${DOCKERNPM} run build-full
npm-install: ## build assets for production
	@echo "--> Install npm dependencies"
	${DOCKERNPM} i
npm-add: ## build assets for production
	@echo "--> Install npm dependency"
	${DOCKERNPM} i ${dep}

assets-install:
	$(DOCKERPHP) ./bin/console cache:clear
	$(DOCKERPHP) ./bin/console assets:install --symlink --relative

security-check:
	$(DOCKEREXEC) php /usr/local/bin/symfony check:security

# Setup and Teardown
setup-dev: docker-up db-dev npm-build-full assets-install

setup-dev-full: docker-up composer-install db-dev db-test npm-build-full assets-install ## full dev environment setup (docker + composer packages + db + assets)

setup-prod: docker-up composer-install npm-build-full

# Database
db-dev: ## setup database for development
	@echo "--> Setting database up for development"
	$(DOCKERPHP) ./bin/console doctrine:database:drop --force --if-exists -e dev
	$(DOCKERPHP) ./bin/console doctrine:database:create -e dev
	make fixtures-update

db-test: ## setup database for testing
	@echo "--> Setting database up for testing"
	$(DOCKERPHP) ./bin/console doctrine:database:drop --force --if-exists -e test
	$(DOCKERPHP) ./bin/console doctrine:database:create -e test
	make test-fixtures-update

tests:
	@echo "--> Testing Pest config"
	make pest-run
	@echo "--> Testing Cypress config"
	make cypress-run

pest-run:
	@echo "--> Testing Pest config"
	$(DOCKERPHP) ./vendor/bin/pest --compact --parallel

pest-run-verbose:
	@echo "--> Testing Pest config"
	$(DOCKERPHP) ./vendor/bin/pest --parallel --display-deprecations --display-errors --display-warnings

pest-bail:
	@echo "--> Testing Pest config"
	$(DOCKERPHP) ./vendor/bin/pest --bail

pest-dirty:
	@echo "--> Testing Pest config"
	$(DOCKERPHP) ./vendor/bin/pest --dirty

pest-retry:
	@echo "--> Testing Pest config"
	$(DOCKERPHP) ./vendor/bin/pest --retry

pest-profile:
	@echo "--> Testing Pest config"
	$(DOCKERPHP) ./vendor/bin/pest --profile

pest-todos:
	@echo "--> Testing Pest config"
	$(DOCKERPHP) ./vendor/bin/pest --todos

pest-run-one:
	@echo "--> Testing ${NAME}"
	$(DOCKERPHP) ./vendor/bin/pest ${NAME}

pest-run-unit:
	@echo "--> Testing PestPHP Unit config"
	$(DOCKERPHP) ./vendor/bin/pest --compact --parallel --group=unit

pest-run-integration:
	@echo "--> Testing PestPHP Integration config"
	$(DOCKERPHP) ./vendor/bin/pest --compact --parallel --group=integration

pest-run-application:
	@echo "--> Testing PestPHP Application config"
	$(DOCKERPHP) ./vendor/bin/pest --compact --parallel --group=application

pest-run-architecture:
	@echo "--> Testing PestPHP Architecture config"
	$(DOCKERPHP) ./vendor/bin/pest --compact --parallel --group=architecture

fixtures-update:
	@echo "--> Resetting fixtures on MySQL"
	$(DOCKERPHP) ./bin/console doctrine:migration:migrate --no-interaction --query-time -e dev
	$(DOCKERPHP) ./bin/console doctrine:fixtures:load --no-interaction -e dev

test-fixtures-update:
	@echo "--> Resetting test fixtures on MySQL"
	$(DOCKERPHP) ./bin/console doctrine:migration:migrate --no-interaction --query-time -e test
	$(DOCKERPHP) ./bin/console doctrine:fixtures:load --no-interaction -e test

# Coding Standards
cs-check:
	@echo "--> PHPCSFIXER : CHECK"
	$(DOCKERPHP) ./vendor/bin/php-cs-fixer fix src --dry-run --diff --no-interaction

cs-fix:
	@echo "--> PHPCSFIXER : FIX FIX"
	$(DOCKERPHP) ./vendor/bin/php-cs-fixer fix src
	$(DOCKERPHP) ./vendor/bin/php-cs-fixer fix config

stan:
	$(DOCKERPHP) ./vendor/bin/phpstan analyse src --level=6

prepare-deploy-files:
	rm docker-compose.override.yml

# MKCert (local SSL certificat)
install-mkcert:
	./bin/mkcert -install

regenerate-mkcert:
	rm -rf config/docker/images/nginx/localhost.crt
	rm -rf config/docker/images/nginx/localhost.key
	export LOCALIP=`ip route get 8.8.8.8 | head -1 | cut -d ' ' -f 7`
	./bin/mkcert --cert-file config/docker/images/nginx/localhost.crt --key-file config/docker/images/nginx/localhost.key $(LOCALIP) localhost 127.0.0.1 ::1 kop.local

wait-for-mysql:
	$(DOCKERRUN_NODEPS) php bash "wait-for-mysql.sh"

