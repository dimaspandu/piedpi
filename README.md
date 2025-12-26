# Piedpi

## Why This Project Exists

Piedpi was created as a lightweight, educational, and production-conscious PHP codebase that demonstrates how a modern web application can be built **without relying on large frameworks**, while still preserving good engineering principles.

Many MVPs and internal tools start small but fail to scale cleanly because they are either:

* Over-engineered from day one using heavy frameworks
* Under-engineered with no clear architecture or boundaries

Piedpi sits intentionally in the middle.

It is designed to:

* Be easy to understand from the first read
* Encourage correct architectural habits
* Scale gradually from MVP to a more serious system
* Teach how common framework features actually work internally

This repository is not a framework. It is a **reference architecture and learning-oriented codebase**.

---

## Design Philosophy

Piedpi follows a few core ideas:

1. Explicit is better than implicit
2. Simple control flow beats hidden magic
3. Files and directories should explain themselves
4. Production safety matters even for prototypes
5. Core concepts should be readable without IDE assistance

The code favors clarity over cleverness, and correctness over shortcuts.

---

## High-Level Architecture

The application is organized into clear layers:

* **public/** – Entry point and web server boundary
* **app/** – Application logic and core infrastructure
* **config/** – Environment-agnostic configuration
* **views/** – Presentation layer
* **storage/** – Runtime and writable data
* **db/** – Database-related assets (future expansion)

Each layer has a single responsibility and minimal coupling.

---

## Request Lifecycle

A request flows through the system in the following order:

1. Web server routes all requests to `public/index.php`
2. `bootstrap.php` initializes the application
3. Global exception handling is registered
4. The Router matches the HTTP method and path
5. The matched controller method is executed
6. The controller uses Renderer and Widgets to produce output
7. The response is streamed back to the client

There is no hidden middleware stack or container magic. The flow is explicit and debuggable.

---

## Core Components

### Router

The Router uses a **Trie-based structure** to match routes efficiently.

Features:

* Static routes (`/`)
* Dynamic parameters (`/users/:id`)
* Method-based dispatching
* Explicit 404 handling

The Router does not throw exceptions for normal control flow. Not-found routes are rendered directly for performance and clarity.

---

### Renderer

The Renderer is responsible for output streaming and view composition.

Key ideas:

* Output is streamed in chunks
* Views are included explicitly
* No template engine abstraction
* Predictable rendering order

This approach avoids buffering pitfalls and makes rendering behavior easy to reason about.

---

### Widget System

Widgets are small, reusable UI helpers implemented as plain PHP functions.

They:

* Generate simple HTML structures
* Avoid stateful UI logic
* Keep presentation logic close to markup

This pattern scales well for MVPs and internal tools without introducing template engines too early.

---

### Error Handling

Error handling is centralized in `ErrorHandler`.

Responsibilities:

* Global exception handling
* Environment-aware error output
* Clean 404 rendering

Runtime errors and HTTP errors are intentionally separated to avoid leaking internal details.

---

## Configuration

Configuration files live in `config/` and are plain PHP arrays.

* `app.php` controls environment and debug behavior
* `database.php` defines database connection settings

Environment variables are loaded via `.env` files during bootstrap, keeping secrets out of version control.

---

## Engineering Considerations

### Separation of Concerns

Each class and file has a narrow responsibility. Controllers do not handle routing. The Router does not render views. Rendering logic does not manage application state.

### Explicit Control Flow

There are no implicit lifecycle hooks. If code executes, it is because it was called directly.

### Performance

* No reflection
* No container resolution
* Minimal runtime overhead

This makes the codebase suitable for low-latency environments and easy profiling.

### Computer Science Concepts

The project demonstrates practical use of:

* Trie data structures for routing
* Deterministic state transitions
* Stateless request handling
* Controlled side effects

These ideas map directly to how larger frameworks work internally.

---

## Running the Project

Requirements:

* PHP 8.1 or higher

Start the development server:

```bash
php -S localhost:8888 -t public
```

Then open:

```
http://localhost:8888
```

---

## Intended Use Cases

Piedpi is suitable for:

* MVPs and prototypes
* Internal dashboards
* Learning PHP architecture
* Interview or teaching material
* Projects that may later migrate to a full framework

It is not intended to replace Laravel, Symfony, or similar tools, but to complement understanding of them.

---

## Scalability Path

As a project grows, Piedpi can evolve by adding:

* Safe PDO database layer
* Service layer abstractions
* Authentication middleware
* Request and response objects
* Caching and queueing

The existing structure supports these additions without major refactoring.

---

## Philosophy on Growth

Start small, but start clean.

Piedpi is built on the belief that good architecture should not require complexity, only discipline.

---

## License

This project is released under the MIT License.
