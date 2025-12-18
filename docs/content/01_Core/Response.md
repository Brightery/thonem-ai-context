Response & Views

The Response class manages output buffering, template rendering, JSON responses, and HTTP headers. It includes a smart compression engine (GZIP) that activates automatically if the server supports it.

Returning JSON (API)

This method sets the correct headers, handles errors, adds debug info (if profiling is on), and exits the script.
PHP

// Success Response
Response::json(['id' => 1, 'status' => 'saved']);

// Error Response
Response::error('Invalid API Key', true, 401);

Rendering Views

There are two ways to render HTML templates.

1. View (Partials)

Compiles a template file without the main layout.
PHP

Response::view('parts/sidebar', ['menu' => $menuItems]);

2. Render (Full Page)

Compiles the template and injects it into the currently active layout.
PHP

Response::render('dashboard/index', $data, false, 'admin', 'frontend', 'default_layout');

Output Processing

The OutputProcessor automatically runs on final output:

    Lazy Loading: Adds decoding="async" to all images.

    Tag Replacement: Replaces {Thonem-Template:Header} with actual PHP method calls.

Configuration

PHP

// Enable/Disable GZIP Compression programmatically
Response::disableCompression();
Response::enableCompression();