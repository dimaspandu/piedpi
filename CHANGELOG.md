# Changelog

## [1.0.3] – View Layer Refinement & Widget Enhancement

### Added
- Self-closing HTML tag support in Widget renderer (`br/`, `hr/`, etc.)
- Recursive widget children handling (string, array, nested widgets)
- Safe mixed-content rendering (escaped text + trusted widget output)
- Asset routing support for non-public front controller setups

### Improved
- Widget rendering flexibility for complex semantic layouts
- HTML composition without introducing a template engine
- View-layer ergonomics for progressive HTML streaming
- Consistency between streamed chunks and full view rendering

### Notes
This release strengthens the **view and composition layer** of Piedpi.
Widgets can now express complex, semantic HTML structures while
remaining framework-free, stream-safe, and predictable.

---

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
