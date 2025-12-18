# Cron Jobs & Scheduled Tasks

The Cron controller handles the execution of background tasks, maintenance scripts, and scheduled events (like sending newsletters or cleaning caches). It is designed to run indefinitely or until all tasks are complete, overriding standard server timeout limits.

## 1. Configuration

To prevent unauthorized execution or accidental running in development environments, the Cron system is protected by a constant.

In your .env or config file, you must enable it:
```php

// Enable Cron execution
define('CRONJOB', true);
```

If this is set to false, accessing the route will return an error: "Cron job is not active".

## 2. Execution Logic

When the Cron controller starts, it automatically applies the following server overrides to ensure heavy tasks complete successfully:

    Max Execution Time: Set to -1 (Unlimited).

    Memory Limit: Set to -1 (Unlimited).

    User Abort: Ignored (The script continues running even if the HTTP connection is closed).

    Session Tracking: Disabled ($disable_session_tracker = true) to prevent polluting user session logs with cron activity.

## 3. Setting up the Server

You need to trigger the cron URL periodically.

Via cPanel / Linux Crontab (Recommended)

Set this command to run every minute (* * * * *).

```bash
# Using Wget (Standard)
wget -q -O - https://yourdomain.com/cron >/dev/null 2>&1

# OR using PHP CLI (if configured in Request.php)
php /path/to/your/project/index.php cron
```

Via Browser (Manual Trigger)

You can manually trigger the jobs by visiting: https://yourdomain.com/cron

## 4. Debugging & Monitoring

The controller includes a specific mode to visualize the execution status of tasks.

Append ?watch=1 to the URL to view the output in the browser instead of running it silently.
Plaintext

https://yourdomain.com/cron?watch=1

This will render the cron view with a list of processed items and their status.

## 5. Architecture

The Controller delegates the actual processing logic to the Model:
```php
// Controller delegates to Model
$cronModel = new \Model\Cron();
$results = $cronModel->run();
```

This allows the Cron model to handle the logic of checking the database for due tasks, locking them to prevent overlapping executions, and marking them as complete.