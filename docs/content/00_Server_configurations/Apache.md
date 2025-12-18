# Server Configuration

The Thonem framework uses the **Front Controller** pattern, meaning all HTTP requests are routed through `index.php`.

## 1. Apache Setup (`.htaccess`)
The framework comes with a pre-configured `.htaccess` file. Ensure `mod_rewrite` and `mod_headers` are enabled on your server.

### Key Features Enabled:
* **Routing:** Redirects all non-file requests to `index.php`.
* **Caching:** Sets 1-year browser cache for static assets (Images, CSS, JS).
* **Compression:** Enables GZIP/Deflate for text-based responses.
* **CORS:** Allows API access from external domains.
* **Memory:** Increases PHP memory limit to `2256M`.

### Standard `.htaccess`
```apache
php_value memory_limit 2256M

<IfModule mod_expires.c>
  ExpiresActive On

 # Images
  ExpiresByType image/jpeg "access plus 1 year"
  ExpiresByType image/gif "access plus 1 year"
  ExpiresByType image/png "access plus 1 year"
  ExpiresByType image/webp "access plus 1 year"
  ExpiresByType image/svg+xml "access plus 1 year"
  ExpiresByType image/x-icon "access plus 1 year"

  # Video
  ExpiresByType video/webm "access plus 1 year"
  ExpiresByType video/mp4 "access plus 1 year"
  ExpiresByType video/mpeg "access plus 1 year"

  # Fonts
  ExpiresByType font/ttf "access plus 1 year"
  ExpiresByType font/otf "access plus 1 year"
  ExpiresByType font/woff "access plus 1 year"
  ExpiresByType font/woff2 "access plus 1 year"
  ExpiresByType application/font-woff "access plus 1 year"
  ExpiresByType application/font-woff2 "access plus 1 year"

  # CSS, JavaScript
  ExpiresByType text/css "access plus 1 year"
  ExpiresByType text/javascript "access plus 1 year"
  ExpiresByType application/javascript "access plus 1 year"

  # Others
  ExpiresByType application/pdf "access plus 1 year"
  ExpiresByType image/vnd.microsoft.icon "access plus 1 year"
</IfModule>
<IfModule mod_deflate.c>
  # Compress HTML, CSS, JavaScript, Text, XML and fonts
  AddOutputFilterByType DEFLATE application/javascript
  AddOutputFilterByType DEFLATE application/rss+xml
  AddOutputFilterByType DEFLATE application/vnd.ms-fontobject
  AddOutputFilterByType DEFLATE application/x-font
  AddOutputFilterByType DEFLATE application/x-font-opentype
  AddOutputFilterByType DEFLATE application/x-font-otf
  AddOutputFilterByType DEFLATE application/x-font-truetype
  AddOutputFilterByType DEFLATE application/x-font-ttf
  AddOutputFilterByType DEFLATE application/x-javascript
  AddOutputFilterByType DEFLATE application/xhtml+xml
  AddOutputFilterByType DEFLATE application/xml
  AddOutputFilterByType DEFLATE application/json
  AddOutputFilterByType DEFLATE font/opentype
  AddOutputFilterByType DEFLATE font/otf
  AddOutputFilterByType DEFLATE font/ttf
  AddOutputFilterByType DEFLATE image/svg+xml
  AddOutputFilterByType DEFLATE image/x-icon
  AddOutputFilterByType DEFLATE text/css
  AddOutputFilterByType DEFLATE text/html
  AddOutputFilterByType DEFLATE text/javascript
  AddOutputFilterByType DEFLATE text/plain
  AddOutputFilterByType DEFLATE text/xml

  # Remove browser bugs (only needed for really old browsers)
  BrowserMatch ^Mozilla/4 gzip-only-text/html
  BrowserMatch ^Mozilla/4\.0[678] no-gzip
  BrowserMatch \bMSIE !no-gzip !gzip-only-text/html
  Header append Vary User-Agent
</IfModule>
<IfModule mod_rewrite.c>
    Header set Access-Control-Allow-Origin "*"
    Header set Access-Control-Allow-Methods "GET,PUT,POST,DELETE"
    Header set Access-Control-Allow-Headers "Content-Type, Authorization"
    RewriteEngine  On
    #FOR BRIGHTERY
    # Redirect /cdn/posts/ to /cdn/blog/
    RewriteRule ^cdn/posts/(.*)$ /cdn/blog/$1 [L,R=301]

    RewriteBase /
    RewriteCond $1 !^(public\/index\.php|robots\.txt|assets|styles|migration\.php|favicon\.ico)
    RewriteCond %{REQUEST_FILENAME} !^.*\.(jpg|css|js|gif|png|woff|woff2|ttf|svg|eot|html|mp3|mp4|json|map|wsdl|php|jpeg)$ [NC]
    RewriteRule ^(.*)$ index.php/$1 [L]
</IfModule>
```