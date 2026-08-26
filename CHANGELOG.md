# Changelog

## [1.1.1] – Template Cache Resilience

### Fixed
- Stale compiled template paths in `TemplateCache` no longer cause 500 errors after OS temp cleanup
- `Renderer::compileTemplate()` now validates cached file existence before requiring it
- Compiled templates moved from `sys_get_temp_dir()` (system `/tmp`) to project-local `storage/compiled/`
- Eliminated dependency on OS temp directory lifecycle, which caused periodic production failures on shared hosting

### Changed
- `storage/compiled/` is now the canonical location for compiled template files
- `TemplateCache` entries that point to missing files are automatically invalidated and recompiled on next request

### Notes
On shared hosting environments (e.g., Hostinger), system temp directories are periodically purged (typically every 24–48 hours). The previous implementation stored compiled PHP templates in `sys_get_temp_dir()` while keeping only the file paths in `storage/cache/`. When the OS deleted the compiled files but the cache remained populated, subsequent requests attempted to `require()` non-existent files, causing fatal errors and HTTP 500 responses. Clearing `storage/cache/` temporarily resolved the issue until the next OS cleanup cycle.

This fix ensures template compilation survives OS temp directory purges by storing compiled output inside the project directory, and adds a safety check to recompile missing files automatically.

---

## [1.1.0] – Template Engine & Streaming Composition

### Added
- `Renderer::template()` method for Blade-like template rendering
- Automatic template compilation with caching via `TemplateCache`
- Expression interpolation syntax: `{{ $var }}`, `{{ var }}`, `{!! $html }>}`
- `TemplateController` with `/template` and `/template/items` routes
- `template_demo.php` and `template_item.php` example views
- Streaming template rendering inside loops for progressive HTML output

### Improved
- Template cache invalidation tied to source file modification time
- Auto variable prefixing only for simple identifiers (skips function calls)
- Backward compatibility: `Renderer::view()` behavior remains unchanged

### Notes
This release introduces a lightweight template compilation layer to Piedpi.

Templates are compiled to plain PHP before execution, similar to how Blade works internally:
1. Source template is read and parsed
2. Expressions are transformed into escaped `<?= htmlspecialchars(...) ?>` calls
3. Unescaped blocks `{!! ... !!}` are preserved as raw output
4. Compiled output is cached and invalidated when the source changes
5. The compiled file is executed via `require`, preserving streaming behavior

This keeps Piedpi framework-free while adding ergonomic template syntax.
