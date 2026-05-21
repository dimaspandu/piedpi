# Piedpi - Frontend Services

## Overview

This is the **`frontend-services`** branch of Piedpi.

Unlike the `main` branch (which is a full-stack application), this branch is a **lightweight frontend delivery service**. Its primary purpose is to serve pre-built frontend assets (HTML + JavaScript bundles) from the `dist/` directory with clean routing and gzip compression.

It is designed for scenarios where the frontend is built separately (e.g. using Vite, Webpack, or any modern frontend tooling) and you need a simple, framework-free way to host and deliver those builds.

## Purpose of This Branch

- Serve multiple pre-compiled frontend applications from a single PHP endpoint.
- Provide efficient static asset delivery with built-in gzip support.
- Act as a thin delivery layer between a frontend build pipeline and the browser.
- Keep the backend minimal and predictable.

## Key Differences from `main`

| Feature                    | `main` Branch                     | `frontend-services` Branch          |
|---------------------------|-----------------------------------|-------------------------------------|
| Focus                     | Full-stack application            | Frontend asset delivery             |
| Database & API            | Yes                               | No                                  |
| Number of Controllers     | Many                              | Minimal (`DistController`, `HomeController`, `ErrorController`) |
| Middleware Examples       | Available (`AuthMiddleware`, `CsrfMiddleware`, etc.) | Not included (kept minimal)     |
| Routing Style             | Feature-rich                      | Catch-all for frontend assets (`/:name`) |
| Primary Use Case          | Internal tools, MVPs, learning    | Serving built frontend bundles      |

## How It Works

The routing strategy is deliberately simple:

- `GET /` and `GET /hello` → handled by `HomeController`
- All other requests (`/:name`) → routed to `DistController::serve`

`DistController` looks for a file in the `dist/` folder using the route parameter (e.g. `/application` → `dist/application.html`) and serves it with gzip compression when supported by the client.

This pattern allows you to host multiple frontend builds (landing pages, admin panels, SPAs, etc.) from one lightweight PHP application.

## Architecture

The architecture on this branch is intentionally minimal:

- **Entry Point**: `public/index.php`
- **Routing**: Simple router with one catch-all route for frontend assets
- **Asset Serving**: `DistController` + `Renderer::serve()` with gzip support
- **Error Handling**: Centralized 404 and 500 handlers via `ErrorController`

There is no database layer, no complex middleware pipeline, and no API routes. The focus is purely on reliable and efficient frontend delivery.

## Project Directory Structure (Relevant Parts)

```
frontend-services/
├── app/
│   ├── Controllers/
│   │   ├── DistController.php     # Serves files from dist/
│   │   ├── HomeController.php
│   │   └── ErrorController.php
│   └── Core/                      # Router, Renderer, ErrorHandler, etc.
├── config/
├── dist/                          # Pre-built frontend files (*.html)
│   ├── application.html
│   └── product-landing.html
├── public/
├── routes/
│   └── web.php                    # Minimal routing (mostly catch-all)
├── storage/
└── README.md
```

## Running the Project

Requirements:
- PHP 8.1 or higher

Start the development server:

```bash
php -S localhost:8888 -t public
```

Then open:

```
http://localhost:8888
```

Example routes:
- `/` → Home page
- `/application` → Serves `dist/application.html`
- `/product-landing` → Serves `dist/product-landing.html`

## Intended Use Cases

This branch is ideal for:

- Serving frontend builds generated from a separate repository or build pipeline
- Hosting multiple single-page or multi-page applications
- Lightweight frontend delivery without needing Nginx/Apache static file configuration
- Prototyping or internal tools where the frontend is built independently

## Relationship with Other Branches

- `main` → Full-stack reference implementation
- `frontend-services` → **This branch** – focused on frontend asset serving
- `backend-services` → Backend-focused services (if exists)

## Philosophy

Even though this branch is simpler, it still follows Piedpi’s core principles:

- Explicit is better than implicit
- Simple control flow
- No hidden magic
- Production-conscious design (gzip, clean error handling, predictable routing)

---

## License

This project is released under the MIT License.
