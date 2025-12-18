# Db (Query Builder) Manually

The `Db` class is a powerful wrapper around PDO, providing a fluent interface for building SQL queries, handling transactions, and managing pagination. a high-performance wrapper around standard PDO. It provides a fluent interface for building SQL queries, safe parameter binding, and advanced features like subqueries and schema modification.


## 1. Basic Usage

### Initialization
The database connection is automatically handled by the framework using configurations in `Config`. You can access the instance statically.

### Retrieving Data (Select)

**Get All Rows**
```php
$users = Db::getInstance()->get('users');
// OR using the shortcut helper if defined
$users = $db->get('users');
```

### Select (Get)
```php
// Get all users
$users = $db->get('users');

// Get specific columns with limit
$users = $db->get('users', 10, ['id', 'username', 'email']);

// Get a single row
$user = $db->where('id', 1)->row(); // Returns Object
```

### Insert
```php
$id = $db->insert('users', [
    'username' => 'john_doe',
    'email' => 'john@example.com',
    'created_at' => $db->now() // Uses SQL NOW() function
]);
```

### Update
```php
$db->where('id', 1);
$db->update('users', [
    'status' => 'active',
    'login_count' => $db->inc(1) // Increments existing value
]);
```

### DELETE
```php
$db->where('id', 1);
$db->delete('users');
```
## Advanced Querying

### Where Clauses
```php
$db->where('status', 'active')
   ->where('age', 18, '>')
   ->where('role', ['admin', 'editor'], 'IN') // WHERE IN
   ->orWhere('vip', 1);
```




### Joins
```php
// join(table, condition, type)
$db->join('orders o', 'o.user_id = users.id', 'LEFT')
   ->get('users');


$db->join('orders o', 'o.user_id = u.id', 'LEFT')
   ->join('products p', 'p.id = o.product_id', 'INNER')
   ->get('users u', null, 'u.name, p.product_name');
```

### Subqueries
You can generate a subquery object and pass it into a where clause.

```php
// Subquery: Get IDs of users who have ordered
$sub = $db->subQuery('o');
$sub->get('orders', null, 'user_id');

// Main Query: Get users NOT in that list
$db->where('id', $sub, 'NOT IN');
$users = $db->get('users');


````
### Special Functions
*** Time & Intervals ***

Instead of calculating dates in PHP, let the DB do it.
```php
// SQL: ... WHERE expires_at < NOW() - INTERVAL 1 DAY
$db->where('expires_at', $db->interval('-1d'), '<');

// SQL: ... SET updated_at = NOW()
$db->update('posts', ['updated_at' => $db->now()]);
```

Atomic Increments

Avoid race conditions by letting the database handle math.

```php
$db->update('products', [
    'stock' => $db->dec(1) // Decrement stock by 1
]);
```

## 4. Schema Management

The Db class includes methods to modify the database structure programmatically.

Create Table
```PHP

$db->createTable('logs', [
'id' => ['type' => 'int(11)', 'auto_increment' => true, 'primary' => true],
'message' => ['type' => 'text'],
'created_at' => ['type' => 'datetime', 'default' => 'CURRENT_TIMESTAMP']
], ['id']); // Primary Keys
```

Modify Table

```PHP

$db->modifyTable('users',
['phone' => ['type' => 'varchar(20)', 'null' => true]], // Add Column
[], // Modify Column
['old_column'], // Remove Column
['phone_idx' => ['phone']] // Add Index
);
```

*** Check Table Existence ***

```PHP

if (!$db->tableExists('settings')) {
// Run migration...
}
```

## 5. Pagination

 This wrapper calculates total pages and offsets automatically.

```php

$page = $_GET['page'] ?? 1;
$limit = 20;

$db->setPageLimit($limit);
$results = $db->paginate('logs', $page);

// Access pagination data properties after call:
$totalPages = $db->totalPages;
$totalRecords = $db->totalCount;

```

### Automatically calculates offsets and total pages
```php
$page = $_GET['page'] ?? 1;
$db->setPageLimit(20);
$results = $db->paginate('products', $page);

echo "Total Pages: " . $db->totalPages;
echo "Total Records: " . $db->totalCount;
```


### Helper Methods

| Method                  | Description                                                   |                                                
|:------------------------|:--------------------------------------------------------------|
| getValue($table, $col)  | 	Returns a single value from the database.                    |
| has($table)             | 	Returns boolean true if the where condition matches any row. |
| startTransaction()      | 	Begins a PDO transaction.                                    |
| commit() / rollback()   | 	Finalizes or cancels the transaction.                        |
| rawQuery($sql, $params) | 	Executes raw SQL safely with binding.                        |


# Details

| Method        | Example                                          | Description                      |
|:--------------|:-------------------------------------------------|:---------------------------------|
| where         | "$db->where('age', 18, '>=')"                    | Standard comparison.             |
| where_in      | "$db->where_in('id', [1, 5, 9])"                 | Checks if value exists in array. |
| orWhere       | "$db->orWhere('status', 'pending')"              | Adds an OR condition.            |
| like          | "$db->like('title', '%PHP%')",Standard SQL LIKE. |
| where (Array) | "$db->where(['id' => 1, 'active' => 1])"         | Multiple AND conditions.         |


