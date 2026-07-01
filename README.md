# Piedpi - Backend Services

## Overview

This is the **`backend-services`** branch of Piedpi.

Unlike the `main` branch (full-stack) or `frontend-services` (frontend delivery), this branch is a **pure backend/API service**. Its primary focus is to provide a clean, framework-free backend layer with database access, RESTful endpoints, health checks, and robust error handling.

This branch is designed to be consumed by frontend applications (such as those served from the `frontend-services` branch or any external frontend) or other services in a microservices-style architecture.

## Purpose of This Branch

- Deliver a focused backend API layer.
- Handle data operations through a minimal, safe database abstraction.
- Provide health check endpoints for monitoring and orchestration.
- Demonstrate clean API design, error handling, and database integration without heavy frameworks.
- Serve as a backend-only service that can be scaled or deployed independently.

## Key Differences from Other Branches

| Feature                    | `main` Branch                     | `frontend-services` Branch         | `backend-services` Branch (this)     |
|---------------------------|-----------------------------------|------------------------------------|--------------------------------------|
| Focus                     | Full-stack application            | Frontend asset delivery            | Backend / API services               |
| Web Routes (`web.php`)    | Yes                               | Yes (minimal)                      | No                                   |
| API Routes (`api.php`)    | Yes                               | No                                 | Yes (primary focus)                  |
| Database Layer            | Present                           | Not used                           | Core component                       |
| Frontend Serving          | Limited                           | Main purpose (`DistController`)    | None                                 |
| Controllers               | Many                              | Very few                           | API-focused (Health, Item, DBTest, Error) |
| Middleware Examples       | Available                         | Not included                       | Minimal (CORS only)                  |
| Primary Use Case          | Learning full-stack               | Serving pre-built frontend         | Providing backend APIs and data      |

## How It Works

This branch exposes its functionality primarily through `routes/api.php`:

- `GET /` → Health check endpoint (used by load balancers, uptime monitors, etc.)
- `/api/items` endpoints → Full CRUD operations (GET, POST, PUT, DELETE) via `ItemController`
- Development-only debug routes (e.g. `/_debug/db/items`)
- Centralized error handling via `ErrorController`

The database layer (`db/piedpi.sql` + `config/database.php`) provides a minimal, explicit PDO abstraction through `Connection` and the `DB` facade.

CORS middleware is included to support cross-origin requests from frontend clients.

## Architecture

The architecture is intentionally backend-focused:

- **Entry Point**: `index.php` (root level)
- **Routing**: API-only routes defined in `routes/api.php`
- **Controllers**: Handle business logic for health, items, and database testing
- **Database Layer**: Environment-driven configuration with safe PDO access
- **Error Handling**: Explicit 404 and 500 handlers returning consistent responses (JSON-friendly)
- **Middleware**: Lightweight (`CorsMiddleware` in `app/Middleware/`)

There is no view rendering or frontend asset serving. The output is primarily JSON or simple responses suitable for API consumption.

## Project Directory Structure (Relevant Parts)

```
backend-services/
├── app/
│   ├── Controllers/
│   │   ├── HealthController.php
│   │   ├── ItemController.php
│   │   ├── DbTestController.php
│   │   └── ErrorController.php
│   ├── Core/
│   │   ├── Database/          # Connection, DB facade, exceptions
│   │   ├── Router.php
│   │   ├── ErrorHandler.php
│   │   └── Middleware/        # ErrorBoundary only
│   └── Middleware/            # CorsMiddleware (application-level)
├── config/
│   ├── app.php
│   └── database.php
├── db/
│   └── piedpi.sql             # Database schema
├── routes/
│   └── api.php                # All API endpoints
├── index.php                  # Entry point (root level)
├── storage/
└── README.md
```

## Running the Project

Requirements:
- PHP 8.1 or higher
- PDO extension
- MySQL or compatible database (schema provided in `db/piedpi.sql`)

Start the development server:

```bash
php -S localhost:8888
```

Example API calls:

```bash
# Health check
curl http://localhost:8888/

# Get items
curl http://localhost:8888/api/items

# Create item (example payload)
curl -X POST http://localhost:8888/api/items \
  -H "Content-Type: application/json" \
  -d '{"name":"Test Item","price":100}'
```

## Intended Use Cases

This branch is ideal for:

- Providing a backend API for a separate frontend application (e.g. consumed by `frontend-services` or any SPA)
- Microservices architecture where backend and frontend are developed/deployed independently
- Learning how to build clean, minimal APIs and database layers in PHP without frameworks
- Internal tools, admin backends, or data services
- Testing database integration patterns in a controlled, explicit way

## Relationship with Other Branches

- `main` → Full-stack reference implementation (includes both frontend and backend)
- `frontend-services` → Frontend asset delivery (can consume APIs from this branch)
- `backend-services` → **This branch** – focused backend/API services with database support

These branches allow the project to demonstrate different architectural slices of the same core Piedpi principles.

## Philosophy

Even on a backend-focused branch, Piedpi maintains its core values:

- Explicit is better than implicit
- Simple, readable control flow
- Safe and minimal database access (no ORM magic)
- Clear separation of concerns
- Production-conscious design (health checks, error handling, CORS)

This branch shows how the same lightweight philosophy can be applied when the goal is purely backend services.

---

## License

This project is released under the MIT License.
