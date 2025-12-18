# Config & Encryption

The `Config` library manages system settings, database configurations, and AES-256 encryption.

## Configuration Methods

| Method | Usage | Description |
| :--- | :--- | :--- |
| `Config::get($module, $name)` | `Config::get('system', 'site_name')` | Retrieves a single config value. Cached automatically. |
| `Config::set($module, $name, $val)` | `Config::set('system', 'maintenance', 1)` | Saves a config value to the database. |
| `Config::ml(...)` | `Config::ml('system')` | Retrieves multilingual config values using `xLang`. |

## Encryption
The library includes robust AES-256-GCM encryption. **Note:** It requires `CONFIG_SECRET` to be defined and a valid Session Cookie.

### Encrypt Data
```php
try {
    $secret = "MySensitiveData";
    $hash = Config::encrypt($secret);
    // Save $hash to database
} catch (Exception $e) {
    echo "Encryption error: " . $e->getMessage();
}
```

### Decrypt Data

```php
try {
    $original = Config::decrypt($hash);
} catch (Exception $e) {
    // Fails if cookie secret or CONFIG_SECRET has changed
    echo "Decryption failed";
}
```