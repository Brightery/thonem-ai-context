# Language & Localization

The Language class loads JSON translation files from STORAGE_PATH/languages/{code}/.

File Structure

Languages are stored as JSON files. storage/languages/en/users_lang.json:
JSON

{
"welcome_message": "Welcome back, user!"
}

Usage

PHP

// Load specific file
Language::load('users');

// Accessing phrases (Global function helper usually calls this)
echo lang('welcome_message');

Log.md

Logger

The Log class provides a runtime logging mechanism. Currently, it stores logs in memory for the duration of the request (useful for debugging and the Debug Toolbar).

Methods

PHP

// Add a log entry
Log::set("Payment Gateway initialized");

// Add log with specific timestamp
Log::set("Query executed", microtime());

// Retrieve all logs
$allLogs = Log::get();

    Note: For persistent file logging, use the Logger class found in the Debugger module. Log is primarily for runtime flow tracking.