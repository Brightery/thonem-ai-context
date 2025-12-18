Nginx Setup

Nginx provides better performance for high-traffic sites but does not read .htaccess files. You must configure the server block.

Configuration Block

Add this to your sites-available configuration:
```Nginx
server {
listen 80;
server_name yourdomain.com;
root /path/to/thonem;
index index.php;

    # GZIP Compression
    gzip on;
    gzip_types text/plain text/css application/json application/javascript;

    # Front Controller Routing
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Static Asset Caching (1 Year)
    location ~* \.(jpg|jpeg|png|gif|css|js|woff2)$ {
        expires 1y;
        access_log off;
    }

    # PHP Handling
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php-fpm.sock;
        fastcgi_param PHP_VALUE "memory_limit=2256M";
    }
}
```