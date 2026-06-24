.PHONY: up down build restart logs generate generate-topic shell migrate reset-dedup

WEB = docker exec laravel-ai-blog-web

up:
	docker compose up -d

down:
	docker compose down

build:
	docker compose down && docker compose build laravel-web && docker compose up -d

restart:
	docker compose up -d --force-recreate laravel-web

logs:
	docker compose logs -f laravel-web

## Generate a blog post immediately (random topic from NewsAPI)
generate:
	$(WEB) php artisan blog:generate

## Generate with a custom keyword query  e.g. make generate-topic Q="quantum computing"
generate-topic:
	$(WEB) php artisan blog:generate --q="$(Q)"

## Wipe dedup DB so previously-seen articles are eligible again (useful for testing)
reset-dedup:
	docker exec laravel-ai-blog-generator rm -f /data/dedup.db
	@echo "Dedup database cleared."

shell:
	docker exec -it laravel-ai-blog-web bash

migrate:
	$(WEB) php artisan migrate
