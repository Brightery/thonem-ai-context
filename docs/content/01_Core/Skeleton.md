
# UI Helpers (Skeleton.php)

Generates "Shimmer" loading states for better UX before data loads.
```php

// Generate 3 Text Lines
echo Skeleton::generate('textBlock', 1, ['lines' => 3]);

// Generate 4 Product Cards
echo Skeleton::generate('card', 4, ['hasImage' => true]);
```
