
# Cache Library

A simple file-based JSON caching system. It stores cache files in `CACHE_PATH`.

## Basic Usage

### Get (or Create) Cache
The most powerful method is `get`. It attempts to retrieve the cache; if missing or expired, it executes the callback and saves the result.

```php
// Cache a heavy DB query for 1 hour (3600 seconds)
$users = Cache::get('all_active_users', function() {
    // This code only runs if cache is missing or expired
    return User::find(['status' => 'active']);
}, 3600);
```


### Check existence

```php

if (Cache::isCached('my_custom_report')) {
// Do something
}
```
### Clearing Cache

```php

// Clear specific item
Cache::clear('all_active_users');

// Clear EVERYTHING (Be careful)
Cache::clear('*');
```