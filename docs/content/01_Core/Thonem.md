
This section covers the Core System container, the Base Model (ORM), Authentication (User), Validation, and View Management.


## 1. Core System

The `Thonem` class is the heart of the framework. It acts as a **Dependency Injection Container** (Singleton) and the Application Bootstrapper.

### Key Methods

| Method | Description |
| :--- | :--- |
| `Thonem::add($object, $alias)` | Registers a class instance into the system memory. |
| `Thonem::get($alias)` | Retrieves a registered instance (e.g., `Thonem::get('Db')`). |
| `Thonem::run()` | The main execution loop. Loads the loader, boots the app, and runs the router. |
| `Thonem::file($path)` | Safely includes a file and logs it for debugging. |
| `Thonem::helper($name)` | Loads a helper file (checks Modules first, then Core). |
| `Thonem::error404()` | Triggers the system 404 page. |

### Usage
```php
// Accessing the Database anywhere in the app
$db = Thonem::get('Db');

// Loading a specific helper
Thonem::helper('Text');
```

2. Base Controller (ThonemController.php)

All your application controllers should extend ThonemController (which extends Controller). It handles the heavy lifting of initialization.

What it does automatically:

    Database Connection: Throws an exception if DB is missing.

    Session Tracking: Tracking user activity (unless $disable_session_tracker is true).

    Configuration Loading: Loads system config and module-specific config.

    Language Loading: Loads system language files.

    Theme Setup: Prepares the frontend/backend theme paths.

    Breadcrumbs: Adds "Home" to the breadcrumb trail.

Properties

```php

class ProductsController extends ThonemController 
{
    public $_table = 'products'; // Default table for this controller
    public $_title = 'Products'; // Page title
    public $_module = 'store';   // Current module name
}
```
