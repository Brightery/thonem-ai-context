
# User & Authentication

The User class manages sessions, permissions, and user data retrieval.

Retrieval Methods

```php

// Get current logged-in user ID
$id = User::get('user_id');

// Get full user object
$user = User::get();

// Get Session ID
$sid = User::getSessionID();
```

Permissions

Checks user permissions based on their User Group settings.
```php

if (User::permission('can_delete_products')) {
    // Do something
}
```

Helpers

```php

// Get User Image (Checks CDN, then Gravatar, then Default)
$img = User::image();

// Check if current visitor is a bot
if (User::robot()) { ... }
```