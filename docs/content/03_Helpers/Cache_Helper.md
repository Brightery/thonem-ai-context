# Cache Helper

A lightweight wrapper for managing custom file-based caching for specific entities.

## Methods

### `generateCache($data, $entity, $minutes, $type)`
Saves data to a file with an expiration timestamp embedded in the filename.

```php
// Cache an array of products for 30 minutes
generateCache($products, 'homepage_products', 30);

loadCache($entity, $type)
```
Retrieves cached data. It automatically checks the timestamp in the filename. If the file is expired, it deletes it and returns false.
```php

$data = loadCache('homepage_products');

if (!$data) {
    // Cache missed or expired, regenerate it
    $data = $db->get('products');
    generateCache($data, 'homepage_products', 30);
}
```