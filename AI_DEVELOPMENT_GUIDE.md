# Thonem Framework AI Development Guide

## How AI Should Generate Code for Thonem

### 1. Always Extend Base Controllers
```php
// For user areas:
class MyController extends BackstageController

// For admin panel:
class MyController extends AdminController

// For APIs:
class MyController extends ApiController
```

2. Use ThonemModel for Database Operations
```php
class ProductModel extends ThonemModel {
   public $table = 'products';
   public $primary_key = 'id';

    public function findActive() {
    return $this->find(['where' => ['status' => 'active']]);
   }
}
```
3. Form Generation with Form Class
   ```php
   // NEVER write raw HTML inputs
   // ALWAYS use Form:: methods
   echo Form::text('username', $value, ['label' => 'Username']);
   echo Form::select('status', $options, $selected);
   echo Form::media('image', $path, $options);

```
4. Security First
   ```php
   // Validate CSRF token
   if (!CSRF::validate(post('csrf_token'))) {
   redirect(current_url());
   }

// Use Input filtering
$name = Input::post('name'); // Auto XSS filtered

```
5. Use Framework Helpers
```php
   // URL generation
   echo url('products/edit/5');
   echo admin_url('dashboard');

// Config access
$siteName = config('system.site_name');

// Current URL
$current = current_url();
```

6. Event System for Extensibility

```php
   // Inject content
   Event::set('BEFORE_HEAD_CLOSE', '<script>...</script>');

// Output in layout
<?= Event::get('BEFORE_HEAD_CLOSE') ?>
```

7. Caching Strategy
   ```php
   // Cache expensive operations
   $data = Cache::get('expensive_query', function() {
   return $db->heavyQuery();
   }, 3600);
   ```
8. Module Structure 
```text
   modules/{module}/
   ├── Controllers/
   ├── Models/
   ├── Views/
   ├── assets/
   ├── config/
   └── languages/
```


Testing Template for AI:
text
"Create a [Module] module for Thonem with:
1. Backstage controller for management
2. Model with CRUD operations
3. API endpoints
4. Form with proper validation
5. Event hooks for extensibility
6. Caching for performance"
   text

#### **4. `TEST_AI_MODULE.md`** (Example test file)
# Test AI Understanding of Thonem

## Task: Create a "News" Module

The AI should generate:

1. **Database Schema**
```sql
CREATE TABLE news_articles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255),
    slug VARCHAR(255) UNIQUE,
    content TEXT,
    image VARCHAR(500),
    status ENUM('draft', 'published', 'archived'),
    created_at DATETIME,
    updated_at DATETIME
);
```
Model (NewsModel.php)

```php
class NewsModel extends ThonemModel {
    public $table = 'news_articles';
    public $primary_key = 'id';
    public $_list_key = 'id';
    public $_list_title = 'title';
}
```
Controller (NewsController.php)

```php
class NewsController extends BackstageController {
    protected $_table = 'news_articles';
    protected $_title = 'News Articles';
    
    public function index() {
        Breadcrumbs::add('News', url('news'));
        $model = new NewsModel();
        $articles = $model->find(['limit' => 20]);
        $this->view('news/index', ['articles' => $articles]);
    }
}
```
Form (in view)

```php
<?= Form::text('title', $article->title ?? '', ['label' => 'Title']) ?>
<?= Form::rich_text('content', $article->content ?? '', ['label' => 'Content']) ?>
<?= Form::media('image', $article->image ?? '', ['label' => 'Featured Image']) ?>
<?= Form::hidden('csrf_token', CSRF::generate()) ?>
```

API Endpoint (NewsApi.php)

```php
class NewsApi extends ApiController {
    public function list() {
        $model = new NewsModel();
        $articles = $model->find(['where' => ['status' => 'published']]);
        Response::json(['success' => true, 'data' => $articles]);
    }
}
```