# Debugger & Profiler

The `Debugger` class renders a floating toolbar at the bottom of your page, providing insights into Database queries, Requests, Logs, and Performance.

## Enabling the Debugger
In your bootstrap file (e.g., `index.php`), ensure `DEBUG` is true and initialize the debugger.

```php
// 1. Enable Debug Mode
define('DEBUG', true);

// 2. Initialize Panels
if (DEBUG) {
    Debugger::init();
    Timeline::mark('App Start');
}

// 3. Render at end of script
if (DEBUG) {
    Debugger::render();
}
```
Available Panels
```
Panel	Description
Database	Shows all executed SQL queries, execution time, and highlights slow/duplicate queries.
Request	Dumps $_GET, $_POST, $_SERVER, and Headers.
Logs	Shows messages logged via Logger::log().
Timeline	Shows execution milestones marked via Timeline::mark('label').
Error	Captures the last exception or error.
Files	Lists all PHP files included in the request.
```

Logging Custom Data

Add to Log Panel

```php

Logger::log("Payment processed for ID: 50", Logger::INFO);
Logger::log("API failed", Logger::ERROR);
```
Add to Timeline

```PHP

Timeline::mark('Before Heavy Calculation');
// ... complex code ...
Timeline::mark('After Heavy Calculation');

```