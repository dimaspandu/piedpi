# Changelog

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
