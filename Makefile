APP_CONTAINER_NAME := backend
LOCAL_UID := $(shell id -u)
LOCAL_GID := $(shell id -g)
DOMAIN_EXISTS := $(shell grep -i "127.0.0.1 to-do-list.local" /etc/hosts)

install: prepare-env build-clean up prepare-backend

prepare-env: ## Подготовка окружения
			@if [ "$(DOMAIN_EXISTS)" != "127.0.0.1 to-do-list.local" ]; then echo "127.0.0.1 to-do-list.local" | sudo tee -a /etc/hosts; fi; \
			cp .env.example .env; \
			sed -i "s/DEV_LOCAL_UID=1/DEV_LOCAL_UID="${LOCAL_UID}"/" .env; \
            sed -i "s/DEV_LOCAL_GID=1/DEV_LOCAL_GID="${LOCAL_GID}"/" .env; \
            sed -i "s/DB_HOST=db/DB_HOST=postgres/" .env; \

prepare-backend: ## Подготовка приложения
			docker-compose exec $(APP_CONTAINER_NAME) composer install
			docker-compose exec $(APP_CONTAINER_NAME) php artisan key:generate
			docker-compose exec $(APP_CONTAINER_NAME) php artisan migrate:fresh
			docker-compose exec $(APP_CONTAINER_NAME) php artisan db:seed
			docker-compose exec $(APP_CONTAINER_NAME) php artisan optimize
build: ## Сборка контейнеров
		$(shell docker-compose build)

build-clean: ## Чистая сборка контейнеров
		$(shell docker-compose build --no-cache)

up: ## Запуск контейнеров в фоновом режиме
		$(shell docker-compose up -d)

down: ## Отключение контейнеров
		$(shell docker-compose down)

shell: ## Запуск bash в контейнере
		docker-compose exec $(APP_CONTAINER_NAME) bash


