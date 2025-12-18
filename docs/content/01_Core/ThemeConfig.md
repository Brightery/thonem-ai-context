# Theme Configuration (theme.php)

Every theme must have a theme.php file located at styles/theme/{theme_name}/theme.php. This file acts as a manifest, defining the theme's metadata, registered menus, and the global CSS/JS files that should be loaded by the Response class.

File Structure

The file must return an associative array.

```php
return [
// 1. Theme Metadata (Used in Admin Panel)
"information" => [
"name" => "Brightery",
"description" => "Brightery eCommerce Theme",
"thumb" => "styles/admin/assets/images/logo.png",
"author" => "Brightery Team",
"theme_version" => "1.1.0",
"compatible_versions" => ["6.0.0"]
],

    // 2. Menu Locations
    // These appear in the Admin Panel -> Appearance -> Menus
    "menus" => [
        [
            "code" => "header",      // ID used in code: Template::loadMenu('header')
            "template" => "header"   // Template file in /menu_templates/
        ],
        [
            "code" => "footer",
            "template" => "footer"
        ]
    ],

    // 3. Global CSS
    // These files are automatically injected into the <head> by Template::generate()
    "css" => [
        theme_asset("css/bootstrap.css"),
        theme_asset("css/style.css"),
        
        // Conditional Loading (e.g., RTL support)
        LANG == "ar" ? theme_asset("css/rtl.css") : '',
    ],

    // 4. Global JS
    // These are injected before </body>
    "js" => [
        // You can load core system libraries
        module_asset("js/jquery.min.js", "system"),
        module_asset("js/swal.js", "system"),

        // And theme-specific scripts
        theme_asset("js/main.js"),
        theme_asset("js/app.js"),
        
        // Dynamic language file
        JS . "frontend-" . LANG . ".js", 
    ]
];
```

How it works

    Auto-Loading: When ThonemController initializes, it checks the active theme (e.g., "besary").

    Caching: It loads theme.php and caches the result in theme_settings.

    Injection: The Response class reads this cache and calls Template::addCss() and Template::addJs() for every file listed in the array.