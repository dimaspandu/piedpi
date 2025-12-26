# Changelog

## [1.0.2] – HTTP Layer & Middleware Completion

### Added
- Error boundary middleware for safe request execution
- Full HTTP response abstraction (JSON, Stream, File)
- Support for HTTP methods beyond GET (POST, PUT, PATCH, DELETE)
- API routing separation (`routes/web.php` & `routes/api.php`)
- Health check endpoint (`/health`)
- Basic request payload handling (JSON body & query string)

### Improved
- Router execution flow with centralized response sending
- Controller clarity between web and API responses
- Public entrypoint readability and route organization

### Notes
This release completes the **HTTP and middleware layer** of Piedpi.
The system now supports REST-style APIs while keeping rendering
explicit and framework-free.

---

## [1.0.1] - Database Core

### Added
- Safe PDO wrapper
- Centralized database connection
- Transaction boundary
- Database exception isolation

---

## [1.0.0] - Initial Release

### Added
- Trie-based HTTP router with dynamic parameters
- Controller-based architecture
- Progressive HTML streaming via Renderer
- File-based and chunk-based view rendering
- Simple widget/component rendering system
- Centralized error handling (404 / 500)
- Minimal PSR-4–like autoloader
- Zero-dependency test suite

### Philosophy
- Minimal surface area
- Explicit over implicit
- MVP-ready but scalable
- No unnecessary abstractions
