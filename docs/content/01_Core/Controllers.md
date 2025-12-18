# Thonem Controllers

The framework provides three base controllers to handle different interfaces of your application.

## Available Controllers

| Controller | Extends | Purpose | Key Features |
| :--- | :--- | :--- | :--- |
| **BackstageController** | `ThonemController` | Use for User/Member areas. | Auto-redirects to login, tracks Breadcrumbs, loads Form helper. |
| **AdminController** | `ThonemController` | Use for the internal Admin Panel. | Enforces Admin interface, loads Admin scripts. |
| **ApiController** | `ThonemController` | Use for JSON/REST endpoints. | Disables session tracking, sets API response headers. |

## Usage Examples

### 1. Creating a Member Dashboard
```php
class Dashboard extends BackstageController 
{
    public function index() {
        // User is automatically authenticated here
        // Breadcrumbs are already initialized
        $this->view('dashboard/home');
    }
}
```


2. Creating an API Endpoint

```php

class UserApi extends ApiController
{
    public function getProfile() {
        // Session tracker is disabled for performance
        $data = ['id' => 1, 'name' => 'Thonem User'];
        Response::json($data);
    }
}
```

Configuration Options

Properties you can override in your controller:

|Property|	Type|	Default|	Description|
| :--- | :--- | :--- | :--- |
|$disable_session_tracker|	bool|	false|	Disables user activity tracking (Default true for API).|
|$auto_redirect|	bool|	true|	If true, redirects unauthenticated users to login (Backstage only).|
|$_index_view|	string|	'index'|	Default view file for index operations.|


