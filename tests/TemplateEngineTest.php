<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

use App\Core\View\TemplateEngine;

echo "Running TemplateEngine tests...\n";

/*
|-------------------------------------------------
| Test: Variable substitution
|-------------------------------------------------
*/
$template = 'Hello {{ name }}!';
$data = ['name' => 'World'];
$result = TemplateEngine::render($template, $data);
assertEquals('Hello World!', $result, 'Variable substitution with {{ name }}');

/*
|-------------------------------------------------
| Test: Go-style field access {{ .key }}
|-------------------------------------------------
*/
$template = 'Hello {{ .name }}!';
$data = ['name' => 'World'];
$result = TemplateEngine::render($template, $data);
assertEquals('Hello World!', $result, 'Go-style field access {{ .name }}');

/*
|-------------------------------------------------
| Test: Missing key renders as empty string
|-------------------------------------------------
*/
$template = 'Hello {{ missing }}!';
$data = ['name' => 'World'];
$result = TemplateEngine::render($template, $data);
assertEquals('Hello !', $result, 'Missing key renders as empty string');

$template = 'Hello {{ .missing }}!';
$data = ['name' => 'World'];
$result = TemplateEngine::render($template, $data);
assertEquals('Hello !', $result, 'Missing Go-style key renders as empty string');

/*
|-------------------------------------------------
| Test: {{ . }} renders entire context
|-------------------------------------------------
*/
$template = 'Context: {{ . }}';
$data = ['name' => 'World', 'version' => '1.0'];
$result = TemplateEngine::render($template, $data);
assertTrue(str_contains($result, 'World'), 'Full context contains name');
assertTrue(str_contains($result, '1.0'), 'Full context contains version');

/*
|-------------------------------------------------
| Test: {{ if }} truthy
|-------------------------------------------------
*/
$template = '{{ if show }}Visible{{ end }}';
$data = ['show' => true];
$result = TemplateEngine::render($template, $data);
assertEquals('Visible', $result, 'If truthy renders content');

$template = '{{ if show }}Visible{{ end }}';
$data = ['show' => 'yes'];
$result = TemplateEngine::render($template, $data);
assertEquals('Visible', $result, 'If truthy string renders content');

$template = '{{ if show }}Visible{{ end }}';
$data = ['show' => 1];
$result = TemplateEngine::render($template, $data);
assertEquals('Visible', $result, 'If truthy number renders content');

$template = '{{ if show }}Visible{{ end }}';
$data = ['show' => ['item']];
$result = TemplateEngine::render($template, $data);
assertEquals('Visible', $result, 'If truthy array renders content');

/*
|-------------------------------------------------
| Test: {{ if }} falsy
|-------------------------------------------------
*/
$template = '{{ if show }}Visible{{ end }}';
$data = ['show' => false];
$result = TemplateEngine::render($template, $data);
assertEquals('', $result, 'If falsy bool renders empty');

$template = '{{ if show }}Visible{{ end }}';
$data = ['show' => 0];
$result = TemplateEngine::render($template, $data);
assertEquals('', $result, 'If falsy zero renders empty');

$template = '{{ if show }}Visible{{ end }}';
$data = ['show' => ''];
$result = TemplateEngine::render($template, $data);
assertEquals('', $result, 'If empty string renders empty');

$template = '{{ if show }}Visible{{ end }}';
$data = ['show' => []];
$result = TemplateEngine::render($template, $data);
assertEquals('', $result, 'If empty array renders empty');

$template = '{{ if show }}Visible{{ end }}';
$data = ['show' => null];
$result = TemplateEngine::render($template, $data);
assertEquals('', $result, 'If null renders empty');

/*
|-------------------------------------------------
| Test: {{ else }}
|-------------------------------------------------
*/
$template = '{{ if show }}Yes{{ else }}No{{ end }}';
$data = ['show' => true];
$result = TemplateEngine::render($template, $data);
assertEquals('Yes', $result, 'If-else with truthy condition');

$template = '{{ if show }}Yes{{ else }}No{{ end }}';
$data = ['show' => false];
$result = TemplateEngine::render($template, $data);
assertEquals('No', $result, 'If-else with falsy condition');

/*
|-------------------------------------------------
| Test: {{ range }} with {{ . }}
|-------------------------------------------------
*/
$template = '{{ range items }}{{ . }}{{ end }}';
$data = ['items' => ['a', 'b', 'c']];
$result = TemplateEngine::render($template, $data);
assertEquals('abc', $result, 'Range with {{ . }} iterates array');

$template = '{{ range items }}-{{ . }}-{{ end }}';
$data = ['items' => ['a', 'b']];
$result = TemplateEngine::render($template, $data);
assertEquals('-a--b-', $result, 'Range with {{ . }} and delimiters');

/*
|-------------------------------------------------
| Test: {{ range }} with field access {{ .name }}
|-------------------------------------------------
*/
$template = '{{ range users }}{{ .name }}{{ end }}';
$data = ['users' => [['name' => 'Alice'], ['name' => 'Bob']]];
$result = TemplateEngine::render($template, $data);
assertEquals('AliceBob', $result, 'Range with {{ .name }} accesses field');

$template = '{{ range users }}{{ .name }}:{{ .age }}{{ end }}';
$data = ['users' => [['name' => 'Alice', 'age' => 30], ['name' => 'Bob', 'age' => 25]]];
$result = TemplateEngine::render($template, $data);
assertEquals('Alice:30Bob:25', $result, 'Range with multiple field access');

/*
|-------------------------------------------------
| Test: Nesting - if inside range
|-------------------------------------------------
*/
$template = '{{ range users }}{{ if .active }}[{{ .name }}]{{ else }}{{ .name }}{{ end }}{{ end }}';
$data = ['users' => [
  ['name' => 'Alice', 'active' => true],
  ['name' => 'Bob', 'active' => false],
]];
$result = TemplateEngine::render($template, $data);
assertEquals('[Alice]Bob', $result, 'Nested if inside range');

/*
|-------------------------------------------------
| Test: Nesting - range inside range
|-------------------------------------------------
*/
$template = '{{ range groups }}{{ range . }}{{ . }}{{ end }}{{ end }}';
$data = ['groups' => [['a', 'b'], ['c', 'd']]];
$result = TemplateEngine::render($template, $data);
assertEquals('abcd', $result, 'Nested range inside range');

/*
|-------------------------------------------------
| Test: Nesting - if inside if
|-------------------------------------------------
*/
$template = '{{ if a }}{{ if b }}both{{ end }}{{ end }}';
$data = ['a' => true, 'b' => true];
$result = TemplateEngine::render($template, $data);
assertEquals('both', $result, 'Nested if inside if');

$data = ['a' => true, 'b' => false];
$result = TemplateEngine::render($template, $data);
assertEquals('', $result, 'Nested if with inner falsy');

/*
|-------------------------------------------------
| Test: Lookup with current item in range
|-------------------------------------------------
*/
$template = '{{ range items }}{{ . }}{{ end }}';
$data = ['items' => [1, 2, 3]];
$result = TemplateEngine::render($template, $data);
assertEquals('123', $result, 'Range with numeric values');

/*
|-------------------------------------------------
| Test: Primitive data in range context
|-------------------------------------------------
*/
$template = '{{ range items }}{{ . }}{{ end }}';
$data = ['items' => 'string'];
$result = TemplateEngine::render($template, $data);
assertEquals('', $result, 'Range with string (not iterable) returns empty');

$template = '{{ range items }}{{ . }}{{ end }}';
$data = ['items' => 123];
$result = TemplateEngine::render($template, $data);
assertEquals('', $result, 'Range with int (not iterable) returns empty');

/*
|-------------------------------------------------
| Test: Complex template
|-------------------------------------------------
*/
$template = <<<'HTML'
<h1>{{ .title }}</h1>
{{ if .items }}
<ul>
{{ range .items }}
  <li>{{ .name }} - ${{ .price }}</li>
{{ end }}
</ul>
{{ else }}
<p>No items</p>
{{ end }}
HTML;

$data = [
  'title' => 'Products',
  'items' => [
    ['name' => 'Apple', 'price' => 1.50],
    ['name' => 'Banana', 'price' => 0.75],
  ],
];

$result = TemplateEngine::render($template, $data);
assertTrue(str_contains($result, 'Products'), 'Complex template has title');
assertTrue(str_contains($result, 'Apple'), 'Complex template has Apple');
assertTrue(str_contains($result, 'Banana'), 'Complex template has Banana');
assertTrue(str_contains($result, '1.5'), 'Complex template has price');
assertTrue(str_contains($result, '0.75'), 'Complex template has price');

$dataEmpty = ['title' => 'Products', 'items' => []];
$resultEmpty = TemplateEngine::render($template, $dataEmpty);
assertTrue(str_contains($resultEmpty, 'No items'), 'Complex template shows else branch');

echo "\n✅ All TemplateEngine tests passed\n";