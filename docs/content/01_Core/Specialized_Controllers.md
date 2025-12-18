
# Specialized Controllers

SMSGatewayController

Abstract class for implementing SMS providers.
```php

class TwilioController extends SMSGatewayController {
    public function send($sms, $gateway) {
        // Implementation
    }
}
```

SocketController

Basic structure for handling WebSocket connections.
```php

class MySocket extends SocketController {
    function receive($data) {
        // Handle incoming data
    }
}
```
