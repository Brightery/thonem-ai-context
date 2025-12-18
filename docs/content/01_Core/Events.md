# Event System

The `Event` class acts as a simple "Hook" or "Injector" system. It is primarily used to collect data (like HTML, Scripts, or Strings) from different parts of the application and output them together in a specific location (layout).

## Methods

| Method | Description |
| :--- | :--- |
| `Event::set($key, $value)` | Appends `$value` to the array of events at `$key`. |
| `Event::get($key)` | Implodes all values assigned to `$key` and returns a string. |

## Usage Example

### 1. Injecting Scripts (e.g., from a Controller)
```php
// In a controller or a view partial
Event::set("BEFORE_HEAD_CLOSE", '<script src="analytics.js"></script>');
Event::set("BEFORE_HEAD_CLOSE", '<meta name="custom" content="value">');
```


2. Outputting in Layout (e.g., header.php)
```php
<!DOCTYPE html>
<html>
<head>
    <title>My App</title>
    <?= Event::get("BEFORE_HEAD_CLOSE") ?>
</head>
<body>
...
```

