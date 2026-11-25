<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>{{ .title }}</title>
  <style>
    body { font-family: system-ui, sans-serif; max-width: 800px; margin: 2rem auto; padding: 0 1rem; }
    table { width: 100%; border-collapse: collapse; margin: 1rem 0; }
    th, td { padding: 0.75rem; border: 1px solid #ddd; text-align: left; }
    th { background: #f5f5f5; }
    .price { text-align: right; }
    .badge { padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.875rem; }
    .badge-instock { background: #d4edda; color: #155724; }
    .badge-outofstock { background: #f8d7da; color: #721c24; }
    .empty { text-align: center; color: #666; padding: 2rem; }
  </style>
</head>

<body>
  <h1>{{ .title }}</h1>
  <p>Version: {{ .version }}</p>

  {{ if .items }}
  <table>
    <thead>
      <tr>
        <th>Name</th>
        <th>Price</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      {{ range .items }}
      <tr>
        <td>{{ .name }}</td>
        <td class="price">${{ .price }}</td>
        <td>
          {{ if .in_stock }}
          <span class="badge badge-instock">In Stock</span>
          {{ else }}
          <span class="badge badge-outofstock">Out of Stock</span>
          {{ end }}
        </td>
      </tr>
      {{ end }}
    </tbody>
  </table>
  {{ else }}
  <div class="empty">
    <p>No items available</p>
  </div>
  {{ end }}

  {{ if .show_debug }}
  <details>
    <summary>Debug: Full Context</summary>
    <pre>{{ . }}</pre>
  </details>
  {{ end }}
</body>

</html>