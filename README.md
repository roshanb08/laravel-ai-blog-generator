# Laravel AI Blog Generator

> A free, self-hosted blog that writes itself — powered by real breaking news and open-source LLMs.

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-22c55e?style=flat-square)](LICENSE)
[![Docker](https://img.shields.io/badge/Docker-ready-2496ED?style=flat-square&logo=docker&logoColor=white)](https://hub.docker.com)
[![OpenRouter](https://img.shields.io/badge/OpenRouter-free_models-6D28D9?style=flat-square)](https://openrouter.ai)
[![AI Service](https://img.shields.io/badge/ghcr.io-ai--blog--generator%3A1.1.0-0ea5e9?style=flat-square&logo=docker&logoColor=white)](https://github.com/roshanb08/ai-blog-generator/pkgs/container/ai-blog-generator)

---

## What is this?

**Laravel AI Blog Generator** is an open-source, self-hosted blog platform that automatically generates and publishes one article every night — for free. It fetches real breaking news from [NewsAPI](https://newsapi.org), uses any LLM available on [OpenRouter](https://openrouter.ai) (including free models) to write a coherent article, and publishes it to a clean, Medium-like blog — all without you touching a keyboard.

**Stack:** Laravel 13 · PHP 8.3 · MySQL 8 · Redis 7 · Docker · NewsAPI · OpenRouter

---

## How It Works

```
Every night at 02:00 UTC
        │
        ▼
┌────────────────────────────────────────────────────────────────┐
│  Laravel Scheduler  →  php artisan blog:generate              │
│                                                                │
│  POST http://laravel-api:8000/generate-blog                   │
│       { category, country, limit, full_html: false }          │
│                         │                                      │
│                         ▼                                      │
│           ai-blog-generator:1.1.0  (FastAPI)                  │
│           ├─ Fetches top headlines from NewsAPI                │
│           ├─ Deduplicates (URL/title hash + Jaccard sim)       │
│           └─ Calls LLM via OpenRouter → returns HTML article   │
│                         │                                      │
│  Laravel saves post to MySQL → publishes immediately           │
└────────────────────────────────────────────────────────────────┘
        │
        ▼
   Blog index shows the new post at the top
```

---

## Features

- **Free to run** — works with OpenRouter free models, no paid API required
- **Fully autonomous** — fetches real news, writes, and publishes nightly without any input
- **Deduplication** — 3-layer dedup (URL hash → title hash → Jaccard similarity) prevents repeat stories
- **Medium-like design** — clean serif typography, featured post hero, card grid with pagination
- **Production-ready Docker** — single `docker compose up -d` starts everything
- **Fallback chain** — if one news source/country fails, automatically retries with alternatives
- **On-demand generation** — trigger a post any time with a single command

---

## Quick Start

### Prerequisites

- [Docker](https://docs.docker.com/get-docker/) and Docker Compose v2
- A free [NewsAPI key](https://newsapi.org/register) (100 requests/day on the free plan)
- A free [OpenRouter key](https://openrouter.ai/keys) (free models available, no billing required)

### 1. Clone

```bash
git clone https://github.com/roshanb08/laravel-ai-blog-generator.git
cd laravel-ai-blog-generator
```

### 2. Configure

```bash
cp .env.example .env
```

Open `.env` and fill in the required values:

```env
# Leave blank — generated automatically on first boot
APP_KEY=

# Change these from the defaults before going to production
DB_PASSWORD=your-secure-password
DB_ROOT_PASSWORD=your-root-password

# NewsAPI — https://newsapi.org/register (free tier: 100 req/day)
NEWS_API_KEY=your_newsapi_key

# OpenRouter — https://openrouter.ai/keys (free models available)
OPENROUTER_API_KEY=sk-or-...
OPENROUTER_MODEL=google/gemma-4-31b-it:free

# Optional: shown as HTTP-Referer to OpenRouter
OPENROUTER_SITE_URL=https://your-site.com
OPENROUTER_SITE_NAME=My Blog
```

### 3. Start

```bash
docker compose up -d
```

| Container | Role | Port |
|---|---|---|
| `laravel-ai-blog-web` | Laravel blog (Nginx + PHP-FPM) | `80` |
| `laravel-ai-blog-generator` | Blog generation API (NewsAPI + LLM) | internal |
| `laravel-ai-blog-mysql` | MySQL 8.0 | `3306` |
| `laravel-ai-blog-redis` | Redis 7 | internal |

Migrations and an `APP_KEY` are generated automatically on first boot.

### 4. Open the blog

```
http://localhost
```

### 5. Generate your first post

```bash
# Using make
make generate

# Or with Docker directly
docker exec laravel-ai-blog-web php artisan blog:generate

# With a custom keyword filter
make generate-topic Q="quantum computing"
```

The blog will also auto-generate a post every night at 02:00 UTC via the Laravel scheduler.

### Useful make commands

```bash
make up              # start all containers
make down            # stop all containers
make build           # rebuild and restart (after code changes)
make restart         # recreate web container (picks up .env changes)
make logs            # tail web container logs
make generate        # generate a post immediately
make generate-topic Q="your topic"  # generate with a keyword filter
make reset-dedup     # clear the dedup DB (useful during testing)
make shell           # open a bash shell inside the web container
```

---

## Artisan Command

```bash
# Default: top technology headlines, US
php artisan blog:generate

# Override category or country
php artisan blog:generate --category=science --country=gb
php artisan blog:generate --q="quantum computing OR blockchain"
```

Available `--category` values: `business` `entertainment` `general` `health` `science` `sports` `technology`

> **Testing tip:** If you run `blog:generate` multiple times in quick succession, the dedup service will mark those articles as seen for 48 hours. Run `make reset-dedup` to clear the dedup database between test runs.

---

## Local Development (without Docker)

### Prerequisites

- PHP 8.3+, Composer, Node.js 20+
- MySQL 8 and Redis running locally (or via Docker)
- The `ai-blog-generator` API service running — see [roshanb08/ai-blog-generator](https://github.com/roshanb08/ai-blog-generator)

```bash
composer install
cp .env.example .env

# Edit .env:
#   DB_HOST=127.0.0.1
#   REDIS_HOST=127.0.0.1
#   BLOG_API_URL=http://localhost:8000   ← wherever the api service is running

php artisan key:generate
php artisan migrate
npm install && npm run build
php artisan serve
```

---

## Configuration Reference

### Laravel (`.env`)

| Variable | Description | Default |
|---|---|---|
| `APP_NAME` | Site name shown in the navbar | `Laravel AI Blog Generator` |
| `APP_KEY` | Application encryption key (auto-generated on first boot) | _(empty)_ |
| `BLOG_API_URL` | URL of the `laravel-api` container | `http://laravel-api:8000` |
| `DB_HOST` | MySQL host | `laravel-mysql` |
| `REDIS_HOST` | Redis host | `laravel-redis` |

### AI Blog Generator service (also in `.env`, read by the `laravel-api` container)

| Variable | Description | Default |
|---|---|---|
| `NEWS_API_KEY` | [newsapi.org](https://newsapi.org/register) key | **required** |
| `LLM_PROVIDER` | `openrouter` or `openwebui` | `openrouter` |
| `OPENROUTER_API_KEY` | [openrouter.ai](https://openrouter.ai/keys) key | **required** |
| `OPENROUTER_MODEL` | Model slug | `google/gemma-4-31b-it:free` |
| `OPENROUTER_SITE_URL` | Sent as HTTP-Referer to OpenRouter | _(empty)_ |
| `OPENROUTER_SITE_NAME` | Sent as X-Title header | `Laravel AI Blog Generator` |
| `LLM_TIMEOUT` | Per-attempt LLM timeout (seconds) | `90` |
| `LLM_TEMPERATURE` | Sampling temperature | `0.7` |
| `LLM_MAX_TOKENS` | Max tokens in LLM response | `2048` |
| `REQUEST_TIMEOUT` | Total request budget (seconds) | `120` |
| `DEDUP_TTL_HOURS` | Hours before a seen article is eligible again | `48` |
| `DEDUP_TITLE_SIMILARITY_THRESHOLD` | Jaccard similarity cutoff for near-duplicate titles | `0.65` |
| `MAX_NEWS_FETCH` | Max articles fetched per NewsAPI call | `20` |

### Free LLM models on OpenRouter

| Model | Notes |
|---|---|
| `google/gemma-4-31b-it:free` | Recommended — reliable HTML output |
| `openai/gpt-oss-20b:free` | Fast, good instruction following |
| `nvidia/nemotron-3-super-120b-a12b:free` | Higher quality, slower |

---

## Project Structure

```
├── app/
│   ├── Console/Commands/
│   │   └── GenerateBlogPost.php   # Calls api service, saves to DB, handles fallbacks
│   ├── Http/Controllers/
│   │   └── BlogController.php     # Paginated index (9/page) + post show
│   └── Models/
│       └── BlogPost.php           # getTagsAttribute splits keywords CSV → array
├── database/migrations/
│   └── 2025_01_01_000000_create_blog_posts_table.php
├── resources/views/blog/
│   ├── index.blade.php            # Medium-like listing with featured post + pagination
│   └── show.blade.php             # Post view with hero image and sources list
├── routes/
│   ├── web.php                    # / and /blog/{slug}
│   └── console.php                # Scheduler: daily at 02:00 UTC
├── docker/
│   ├── nginx.conf                 # Listens on port 8000, proxies to php-fpm
│   ├── supervisord.conf           # Manages nginx, php-fpm, queue worker, scheduler
│   ├── entrypoint.sh              # Waits for DB, migrates, generates APP_KEY, starts supervisord
│   └── php.ini
├── docker-compose.yml             # 4 services: web, api, mysql, redis
├── Dockerfile
├── Makefile                       # Convenience commands
└── .env.example
```

---

## Contributing

Contributions are welcome. Please open an issue before starting significant work.

Ideas for first contributions:

- [ ] RSS feed at `/feed`
- [ ] Sitemap at `/sitemap.xml`
- [ ] Tag/category filtering on the index page
- [ ] Dark mode toggle
- [ ] Admin panel to trigger generation and manage posts

---

## License

MIT — see [LICENSE](LICENSE) for details.

---

<p align="center">Built with Laravel · Powered by NewsAPI and OpenRouter</p>
