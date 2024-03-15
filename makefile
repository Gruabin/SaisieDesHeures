# Executables (local)
SHELL=/bin/bash
DC = sudo docker compose -f docker-compose.yml -f docker-compose.local.yml
APP = $(DC) exec -it php

# Misc
.DEFAULT_GOAL = help
.PHONY        : help build up start down logs sh composer vendor sf cc

## —— 🎵 🐳 The Symfony Docker Makefile 🐳 🎵 ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9\./_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— 🔧 🐳 Docker environnements 🐳 🔧 ——————————————————————————————————
dcb: ## Build docker containers
	@$(DC) build --no-cache

dcu: ## Start docker containers
	@$(DC) up -d

dcd: ## Dismount docker containers
	@$(DC) down

dcub: ## Build and start docker containers
	@$(DC) up -d --build

dcps: ## Show docker containers
	@sudo docker ps

dci: ## Start a bash in the container, exemple make dci php
	@$(DC) exec -it php bash

dcclean: ## Clean docker >24h
	@ sudo docker container prune --filter "until=24h" -f \
		&& sudo docker image prune --all --filter "until=24h" -f \
		&& sudo docker volume prune --filter "label!=keep" -f \
		&& sudo docker network prune --filter "until=24h" -f

dclog: ## Show live logs
	@$(DC) logs --tail=0 --follow

## —— Linter 🧙 ——————————————————————————————————————————————————————————————
linter:	rector cs twigcs # Run linters

rector: ## Run rector
	@$(APP) php ./vendor/bin/rector process

cs: ## Run CS fixer
	@$(APP) php ./vendor/bin/php-cs-fixer fix --config=.php_cs.dist

twigcs: ## Run twigcs
	@$(APP) php ./vendor/bin/twigcs

## —— Composer 🧙 ——————————————————————————————————————————————————————————————
cp: ## Run composer, example make composer c=req symfony/orm-pack
	@$(eval c ?=)
	@$(APP) composer $(c)

cpi: ## Run composer
	@$(APP) composer install \
		&& $(DC) exec -it node npm install


cpu: ## Run composer
	@$(APP) composer update \
		&& $(DC) exec -it node npm update

## —— CHMOD 🧙 ——————————————————————————————————————————————————————————————
chown: ## Chown local files
	@sudo chown -R ${shell whoami} * .*

chmod: ## Chmod local files
	sudo find . -type f -exec chmod 0644 {} \; && sudo find . -type d -exec chmod 0755 {} \; && sudo find . -type f -iname "*.sh" -exec chmod +x {} \;

## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
sf: ## List all Symfony commands example: make sf c=about
	@$(eval c ?=)
	@$(APP) php bin/console $(c)

sfmm: ## Create a new migration based on database changes
	@$(APP) php bin/console make:migration

sfdmm: ## Execute migrations
	@$(APP) php bin/console doctrine:migrations:migrate