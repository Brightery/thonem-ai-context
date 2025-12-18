
# Model 

The ThonemModel provides a powerful abstraction over the Db class. It handles finding, saving, caching, and hierarchical (tree) data.

Configuration

```php

class User extends ThonemModel 
{
    public $table = 'users';
    public $primary_key = 'user_id';
    
    // For Listing/Dropdowns
    public $_list_key = 'user_id';   // The <option value="...">
    public $_list_title = 'username'; // The <option>Text</option>
}
```

Fetching Data (find)

The find method accepts a robust array of filters.
```php

$model = new User();

$users = $model->find([
    'where' => ['status' => 'active'],
    'like' => ['username' => 'john'],
    'limit' => 10,
    'order_by' => ['created_at' => 'DESC'],
    'join' => [
        ['user_profiles', 'user_profiles.user_id = users.id', 'LEFT']
    ]
]);
```

CRUD Operations

```php

// Find by ID
$user = $model->findById(1);

// Save (Insert or Update)
// If primary key is present in data or passed as 2nd arg, it updates.
$id = $model->save([
    'username' => 'new_user',
    'email' => 'mail@example.com'
]);

// Delete
$model->deleteById(1);
$model->deleteById(1, true); // Soft delete (sets deleted=1)
```

Tree & Lists

```php

// Get a simple key=>value array for dropdowns
$options = $model->getList();

// Get a recursive tree (for categories)
$tree = $model->getTree('name');
```
