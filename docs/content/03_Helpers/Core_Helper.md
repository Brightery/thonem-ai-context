# Core Helper

The Core helper is a collection of global shortcut functions that wrap complex library logic into simple, one-line commands.

1. Configuration & Input

| Function           | 	Description                             | 	Example                 |
|:-------------------|:-----------------------------------------|:-------------------------|
| config($key, $val) | 	Get or Set a configuration value.       | 	config('site_title')    |
| cookie($key, $val) | 	Get or Set a cookie (Default 1 year).   | 	cookie('theme', 'dark') |
| post($key)         | 	Safe wrapper for $_POST (XSS filtered). | 	post('username')        |
| get($key)          | 	Safe wrapper for $_GET.                 | 	get('id')               |
| req($key)          | 	Wrapper for $_REQUEST.                  | 	req('token')            |
| ajax()             | 	Returns true if request is AJAX.        | 	if(ajax()) { ... }      |

2. URLs & Redirection

| Function              | 	Description                         |
|:----------------------|:-------------------------------------|
| url($path)            | 	Generates a fully qualified URL.    |
| admin_url($path)      | 	Generates URL prefixed with /admin. |
| current_url()         | 	Returns the current full URL.       |
| redirect($path)       | 	Redirects header and exits script.  |
| admin_redirect($path) | 	Redirects to an admin path.         |

```php

// Generate: [https://yoursite.com/en/products](https://yoursite.com/en/products)
echo url('products');

// Redirect
if (!User::get()) redirect('login');
```

3. Data & JSON

Shortcuts for handling JSON and Objects.

    je($data): Alias for json_encode (Unescaped Unicode/Slashes).

    jd($json): Alias for json_decode (Returns object).

    pr($data): Alias for print_r.

    ao($obj, 'path.to.key'): Access Object using dot notation.

```php

    // Safely access $user->address->city
    $city = ao($user, 'address.city', 'Default City');
```

4. Time & Date

Function	Description
now()	Returns current Y-m-d H:i:s.
timeAgo($date)	Returns human readable string (e.g., "2 hours ago").
showCountdown($min, $start)	Returns "1 Hour, 5 Minutes" remaining.
dateFormat($date)	Formats date based on system date_format config.

5. Assets & Images

Smart Image Tag (eimg)

Generates an <img> tag with lazy loading, CDN support, and placeholders.


```php

echo eimg('products/shoe.jpg', [
'width' => 300,
'height' => 300,
'class' => 'rounded',
'lazy' => true // Default is true
]);
```

Module Assets (module_asset)

Used to load assets from specific modules.

    JS Files: Automatically minifies and hashes them for caching.

    CSS/Img: Returns the direct URL.

```php

// Loads: /modules/shop/assets/js/cart.js (Minified)
echo '<script src="' . module_asset('js/cart.js', 'shop') . '"></script>';
```

AI Image Generation (aiImg)

Generates an image via OpenAI if it doesn't exist locally, saves it, and returns the URL.
```php

echo aiImg("A futuristic city skyline", 800, 600);
```
6. Communication

Email (email)

Wrapper for PHPMailer.
```php

email([
'to' => 'user@example.com',
'subject' => 'Welcome',
'message' => '<h1>Hello</h1>',
'template' => 'welcome_email', // Optional view file
'data' => ['name' => 'John']   // Variables for template
]);
```

SMS (sms)

Sends SMS via the configured default gateway.
```php

sms([
'to' => '+1234567890',
'message' => 'Your OTP is 1234'
]);
```

Notifications (notification)

Sends a notification across multiple platforms (Site, Email, Push, SMS) based on user preferences.
```php

notification([
'user_id' => 1,
'title' => 'New Order',
'text' => 'You have received a new order #500',
'term' => 'order_created', // Key for user preference settings
'url' => url('orders/500')
]);
```

7. File Uploads (upload)

Handles file uploads to the CDN path.
```php

$result = upload([
'field' => 'avatar',       // $_FILES['avatar']
'path' => 'users',         // /public/cdn/users/
'allowed_types' => ['jpg', 'png'],
'maxSize' => 5000          // KB
]);

if ($result->success) {
echo $result->file; // Filename
}
```

8. Security & Strings

   password($pw, $hash): Hashing (BCRYPT) or Verifying passwords.
```php

// Hash
$hash = password('secret');

// Verify
if (password('secret', $hash)) { ... }
```

randomString($len): Generates random alphanumeric string.

getAuthToken(): Retrieves Bearer token from Headers or Cookies.