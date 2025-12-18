# CSRF Protection

Cross-Site Request Forgery (CSRF) protection prevents malicious exploits where unauthorized commands are transmitted from a user that the web application trusts.

## Usage

### 1. Generating a Token
In your form generation (or automatically handled if using a framework helper):

```php
<input type="hidden" name="csrf_token" value="<?= CSRF::generate() ?>">
```
2. Validating a Request

In your Controller logic (typically for POST requests):

```php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$token = $_POST['csrf_token'] ?? '';

    if (!CSRF::validate($token)) {
        die("Security Error: Invalid or expired CSRF token.");
    }
    
    // Proceed with save...
}
```

Internal Logic

The token consists of:

    Hashed User Agent (prevents session hijacking).

    Timestamp (enforces expiration).

    HMAC Signature (ensures integrity using CSRF_SECRET_KEY).