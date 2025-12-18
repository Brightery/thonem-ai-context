
# Breadcrumbs

Manages the navigation trail (e.g., Home > Dashboard > Settings).

## Methods

| Method | Description |
| :--- | :--- |
| `Breadcrumbs::add($title, $url)` | Adds a node to the trail. `$title` can also be an array of `['Title' => 'url']`. |
| `Breadcrumbs::render()` | Returns the trail array and **removes** the last item (active page). |

## Example
```php
// In your Controller
Breadcrumbs::add("Home", "/");
Breadcrumbs::add("Products", "/products");
Breadcrumbs::add("Edit Product"); // No URL for current page

// In your View
$trail = Breadcrumbs::render();
// Returns array structure for looping