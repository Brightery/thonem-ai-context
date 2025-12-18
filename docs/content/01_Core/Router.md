# Router

The `Router` class maps URLs to Controllers and Methods. Thonem supports two routing strategies: **Explicit Definition** (Laravel-style) and **Auto-Discovery** (CodeIgniter-style).

## 1. Explicit Routing (Recommended)
You can define routes manually for precise control.

```php
// Basic Verbs
Router::get('products', 'ProductController@index');
Router::post('products/save', 'ProductController@save');
Router::delete('products/{:num}', 'ProductController@delete');

// Grouping with Middleware/Prefix
Router::group(['prefix' => 'api/v1', 'middleware' => ['AuthMiddleware']], function() {
    Router::get('user', 'ApiController@user');
});
```

Wildcards

Pattern	Matches	Regex
{:num}	Numbers only	([0-9]+)
{:any}	Anything except slashes	([^/]+)
{:uuid}	UUID String	([a-f0-9]{8}-...)

Here is the complete documentation for the new libraries and core files you uploaded, formatted as a single Markdown block for easy copy-pasting.

I have categorized them into Core, Helpers, and Modules/Base Classes to match your existing documentation structure.
Markdown

# Documentation Update - Part 3

## Folder: content/01_Core

### Request.md
# Request Handling

The `Request` class is the lifecycle entry point of the application. It analyzes the incoming HTTP request, determines the URI, sets up the environment (CLI vs HTTP), handles JSON input automatically, and manages Multi-language routing.

## Key Features

- **CLI Detection:** Automatically detects if the script is running from the command line.
- **JSON Body Parsing:** Automatically merges JSON payloads (typical in React/Vue apps) into `$_POST`.
- **Language Routing:** Detects language prefixes (e.g., `/en/home`) and sets the system language accordingly.

## Methods

| Method | Description |
| :--- | :--- |
| `Request::run()` | The main trigger. It runs validations, loads Composer, and initiates the App. |

## Automatic Behaviors

1.  **Maintenance Mode:** Checks the `CLOSE` constant.
2.  **Asset Handling:** If a request looks like a file (`.css`, `.js`) but doesn't exist, it triggers a 404 immediately to save processing power.
3.  **Host Validation:** Redirects users to the primary `URL` defined in config if they access via a different alias (SEO protection).

---

### Router.md
# Router

The `Router` class maps URLs to Controllers and Methods. Thonem supports two routing strategies: **Explicit Definition** (Laravel-style) and **Auto-Discovery** (CodeIgniter-style).

## 1. Explicit Routing (Recommended)
You can define routes manually for precise control.

```php
// Basic Verbs
Router::get('products', 'ProductController@index');
Router::post('products/save', 'ProductController@save');
Router::delete('products/{:num}', 'ProductController@delete');

// Grouping with Middleware/Prefix
Router::group(['prefix' => 'api/v1', 'middleware' => ['AuthMiddleware']], function() {
    Router::get('user', 'ApiController@user');
});

Wildcards

Pattern	Matches	Regex
{:num}	Numbers only	([0-9]+)
{:any}	Anything except slashes	([^/]+)
{:uuid}	UUID String	([a-f0-9]{8}-...)

2. Auto-Discovery (Legacy)

If no explicit route matches, the Router parses the URL segments: http://site.com/{Module}/{Controller}/{Method}/{Param}
PHP

// URL: /shop/cart/add/5
// Calls: Shop module -> CartController -> add(5)

Middleware

You can attach middleware checks to specific routes.
PHP

Router::middleware('admin/dashboard', function() {
    if (!User::isAdmin()) return false;
    return true;
});
