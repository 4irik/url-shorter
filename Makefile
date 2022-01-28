SHELL = /bin/bash
DC_RUN_ARGS = --rm --user "$(shell id -u):$(shell id -g)"

help: ## Show this help
	@printf "\033[33m%s:\033[0m\n" 'Available commands'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z0-9_-]+:.*?## / {printf "  \033[32m%-18s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

build: ## Build containers
	docker-compose build

up: ## Start containers
	docker-compose up -d
	@printf "\n   \e[30;42m %s \033[0m\n\n" 'Navigate your browser to ⇒ http://127.0.0.1:8080';

down: ## Stop containers
	docker-compose down

restart: down up ## Restart all containers

shell:
	docker-compose run $(DC_RUN_ARGS) app sh
