# Request Handling

The `Request` class is the lifecycle entry point of the application. It analyzes the incoming HTTP request, determines the URI, sets up the environment (CLI vs HTTP), handles JSON input automatically, and manages Multi-language routing.

## Key Features

- **CLI Detection:** Automatically detects if the script is running from the command line.
- **JSON Body Parsing:** Automatically merges JSON payloads (typical in React/Vue apps) into `$_POST`.
- **Language Routing:** Detects language prefixes (e.g., `/en/home`) and sets the system language accordingly.

## Methods

| Method | Description |
| :--- | :--- |
| `Request::run()` | The main trigger. It runs validations, loads Composer, and initiates the App. |

## Automatic Behaviors

1.  **Maintenance Mode:** Checks the `CLOSE` constant.
2.  **Asset Handling:** If a request looks like a file (`.css`, `.js`) but doesn't exist, it triggers a 404 immediately to save processing power.
3.  **Host Validation:** Redirects users to the primary `URL` defined in config if they access via a different alias (SEO protection).
