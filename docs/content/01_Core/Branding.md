
# Branding

Centralizes the identity of your application (Logos, Titles, Links).

## Easy Access Methods

| Method | Returns | Default |
| :--- | :--- | :--- |
| `Branding::title()` | String | "Thonem" |
| `Branding::logo()` | URL | `cdn("system/logo.png")` |
| `Branding::favIcon()` | URL | `cdn("default.png")` |
| `Branding::copyrights()` | HTML | Standard Thonem Copyright |
| `Branding::twitter()` | String | "@Thonem" |

## Quick Links
Useful for footers or sidebars.

```php
$links = Branding::quickLinks();
foreach($links as $link) {
    echo "<a href='{$link->link}'>{$link->name}</a>";
}
```