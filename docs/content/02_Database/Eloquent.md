# Eloquent ORM (Illuminate)

The framework includes the `Database` class, which initializes the Illuminate Database component (used by Laravel). This allows you to use the Eloquent ORM and Query Builder if you prefer object-oriented models over the raw `Db` wrapper.

## Configuration
The `Database` class automatically reads connection settings from `Config` (system module) or `Thonem::config()`.

**Default connection parameters:**
- Driver: `mysql`
- Host: `localhost` (or from `Db.default.hostname`)
- Database: `install` (or from `Db.default.database`)
- User: `root` (or from `Db.default.username`)

## Initialization
Eloquent is initialized automatically in the `Database::config()` method.

```php
// Manually initializing (if not auto-loaded)
Database::config();
```

Creating Models

Extend Illuminate\Database\Eloquent\Model to create your models.
```php

use Illuminate\Database\Eloquent\Model;

class User extends Model {
    protected $table = 'users';
    public $timestamps = false; // Set to true if you have created_at/updated_at
}
```

Usage Examples

Basic Queries

```php

// Get all users
$users = User::all();

// Find by Primary Key
$user = User::find(1);

// Where clause
$activeUsers = User::where('status', 'active')
                   ->orderBy('created_at', 'desc')
                   ->take(10)
                   ->get();
```

Capsule Manager (Query Builder)

You can access the raw Capsule instance via Database::$instance if you need to run schema operations or raw queries through Illuminate.

```php

use Illuminate\Database\Capsule\Manager as DB;

// Raw Fetch
$results = DB::table('users')->where('id', '>', 10)->get();
```
