
# Template & View Engine

Manages the HTML <head>, assets, and theme logic.

Managing Assets

In your Controller or View:
```php

// Add CSS
Template::addCss(cdn('css/custom.css'));

// Add JS
Template::addJs(cdn('js/app.js'));
```

Outputting in Layout

In your header.php file:
```php

<head>
    <?= Template::generate() ?> <?= Template::css() ?>
</head>
<body>
    ...
    <?= Template::js() ?>
</body>
```

Loading Elements

Loads theme-specific partials.
```php

// Loads themes/{theme}/layout/parts/loader.php
echo Template::loader();

// Loads specific menu from DB
echo Template::loadMenu('main_menu');
```
