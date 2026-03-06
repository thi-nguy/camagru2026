# camagru2026
Likewise Instagram website where users can take photos, upload, like and comment on others' photos.
Stack: PHP 8.3, Nginx, MySQL, HTML, CSS, JavaScript (Vanilla)
Utils: phpMyAdmin, PHP-FPM
# Phase 0 — Docker Setup ✅

## Goal
Set up the dev environment with Docker, Nginx, PHP-FPM, MySQL, Mailhog — get "Hello World" running on `localhost`.
## Stack
- **Web server:** Nginx
- **PHP:** PHP-FPM 8.3
- **Database:** MySQL 8.0
- **GUI:** phpMyAdmin
- **Environment:** Docker + Docker Compose (macOS)
---
## Directory Structure

```
camagru2026/
├── mysql/
│   └── init.sql
├── nginx/
│   └── default.conf
├── php/
│   └── Dockerfile
├── src/
│   ├── public/               ← Nginx document root
│   │   └── index.php         ← Front controller
│   └── app/
├── docker-compose.yml
└── README.md                  

```
---
## docker-compose.yml
- **Nginx** mount `./src/public` → `/var/www/html` — only expose public folder
- **PHP-FPM** mount `./src` → `/var/www` — access to both `app/`, `public/`
- **MySQL** use Docker volume instead of bind mount — faster on macOS
- `depends_on: condition: service_healthy` — PHP starts only after MySQL is really ready.
---
## mysql/init.sql
- **Four tables**: `users`, `photos`, `likes`, `comments`.
- **UUID (`CHAR(36)`)** : All primary keys use `CHAR(36)` UUID format for more security than auto increment.
- **`ON DELETE CASCADE`** — is applied to all foreign keys — if users deletes their account, database will automatically delete related photos, likes, comments.
- **Table `likes`** use composite primary key `(user_id, photo_id)` — ensure 1 user like 1 photo 1 time (one-like-per-photo rule), no extra logic or column `id` needed for this table.
---
## nginx/default.conf
- `try_files $uri $uri/ /index.php?$query_string` — clean URL: static files (.css, .js, .png) are served directly, not through PHP. Other requests fallback to `index.php`, execuse by PHP-FPM. 
- `fastcgi_pass php:9000` — call PHP-FPM through Docker internal network by service name.
- `SCRIPT_FILENAME /var/www/public$fastcgi_script_name` — path must accord to mount of container PHP-FPM.
---
## php/Dockerfile
- PHP official image doesn't have `pdo_mysql` — need to install it to connect to MySQL.
---
## Common Commands

- `docker compose up -d` — start all services in background
- `docker compose ps` — check service status
- `docker compose logs -f` — follow logs
- `docker compose down` — stop services
- `docker compose down -v` — stop and delete all data
---
## Milestone
- `docker-compose up` → open `localhost` → see "Hello World" ✓
---
# Phase 1 — MVC Skeleton + Router ✅
## Goal
- Build the MVC framework: every request goes through `index.php`, gets routed by the Router to the correct Controller, which renders a View.
---
## File Structure
```

camagru2026/
├── mysql/
│   └── init.sql
├── nginx/
│   └── default.conf
├── php/
│   └── Dockerfile             
├── src/
│   ├── public/                ← Nginx document root
│   │   ├── index.php          ← entry point, autoloader, boots the Router
│   │   └── css/
│   │       └── main.css       ← reset, variables, typography, header, footer, navbar, components
│   └── app/
│       ├── Core/
│       │   ├── Router.php     ← addRoute(), dispatch()
│       │   └── helpers.php    ← render()
│       ├── Controllers/
│       │   └── GalleryController.php
│       ├── Views/
│       │   ├── layout/
│       │   │   ├── header.php ← <!DOCTYPE html> + navbar
│       │   │   └── footer.php ← footer + closing HTML tags
│       │   └── GalleryView.php
│       └── routesList.php     ← route definitions
├── docker-compose.yml
└── README.md
```

---

## Request Flow

```
Browser: GET /gallery
    ↓
Nginx: try_files → no file/folder named "gallery" found
    ↓
public/index.php:
    → Load helpers.php (contains render())
    → Register autoloader (automatically finds classes when needed)
    → new Router()  ← autoloader finds and loads core/Router.php
    → Load routes.php (calls addRoute() on $router)
    → $router->dispatch('/gallery')
    ↓
core/Router.php::dispatch():
    → Loop through $routesTable
    → Match '/gallery' → ['GalleryController', 'index']
    → Autoloader loads GalleryController.php
    → new GalleryController() → call index()
    ↓
GalleryController::index():
    → call render('GalleryView')
    ↓
helpers::render():
    → require layout/header.php  (navbar)
    → require GalleryView.php    (page content)
    → require layout/footer.php  (footer + closing HTML)
    ↓
Browser receives complete HTML ✓
```

---
## Key Concepts

**Front Controller Pattern:** Every request goes through a single entry point (`index.php`). The Router decides who handles it.

**MVC (Model - View - Controller):**
- **Model** — Data and database logic (not implemented yet)
- **View** — HTML rendered to the browser (`app/Views/`)
- **Controller** — Business logic, bridge between Model and View (`app/Controllers/`)
- **Infrastructure** — Foundation layer: Nginx, Router, helpers (`core/`, `public/`)

**PHP Conventions (PSR standard):**
- Class: `PascalCase` → `GalleryController`
- Method & variable: `camelCase` → `addRoute`, `$requestUri`
- Constant: `UPPER_SNAKE_CASE` → `DB_HOST`
- Filename: matches class name → `GalleryController.php`

**require vs include:**
- `include` — file not found: warning, execution continues
- `require` — file not found: fatal error, execution stops
- `require_once` — loads only once, prevents duplicate class definitions

**The public folder:** Only `public/` is exposed to the internet by Nginx. `core/` and `app/` are never directly accessible via browser.

---

## Milestone
`localhost/gallery` → Router → `GalleryController::index()` → renders layout with header and footer ✓

---