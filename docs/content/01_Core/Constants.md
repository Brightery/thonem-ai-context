# System Constants & Environment

The `Constants` class initializes the environment variables, paths, and system-wide settings. This is typically run during the bootstrapping phase.

## Key Environment Constants

| Constant | Description | Default / Source |
| :--- | :--- | :--- |
| `URL` | The base URL of the application. | Auto-detected or `getenv("URL")` |
| `DEBUG` | Toggles error reporting and debugging tools. | `getenv("DEBUG")` or Cookie |
| `PROFILER` | Toggles the performance profiler. | `getenv("PROFILER")` or Cookie |
| `CLI_MODE` | Boolean, true if running via Command Line. | `php_sapi_name()` |
| `AJAX` | Boolean, true if request is XMLHttpRequest. | `$_SERVER` headers |

## Path Constants
Use these constants to reference files instead of hardcoding paths.
```
Constant,Path,Purpose
APP_PATH,/app/,Application logic
STORAGE_PATH,/storage/,"Uploads, logs, cache, sessions"
CDN_PATH,/public/cdn/,Publicly accessible assets
THEME_PATH,/styles/theme/,Frontend theme files
```
    
```php
include STYLES_PATH . 'admin/custom.css';
file_put_contents(LOGS_PATH . 'error.log', $data);

// Defined via .env or getenv()
define('CSRF_SECRET_KEY', '...'); 
define('CONFIG_SECRET', '...');