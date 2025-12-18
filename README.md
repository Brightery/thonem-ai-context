# Thonem Framework - AI Module Builder Guide

**Complete Reference for AI Assistants to Build Thonem Modules**

---

## Table of Contents
1. [Framework Overview](#framework-overview)
2. [Module Structure](#module-structure)
3. [Step-by-Step Module Creation](#step-by-step-module-creation)
4. [Core Components](#core-components)
5. [Code Examples](#code-examples)
6. [Best Practices](#best-practices)
7. [Common Patterns](#common-patterns)
8. [Testing](#testing)

---

## Framework Overview

### What is Thonem?
Thonem is a custom PHP framework (v6.0.0) with a modular architecture designed for building enterprise e-commerce and business applications.

### Key Features:
- **Modular Architecture**: 45+ independent modules
- **MVC Pattern**: Separation of Controllers, Models, and Views
- **Custom ORM**: ThonemModel for database operations
- **Form Builder**: Dynamic form generation with validation
- **Security Layer**: Built-in CSRF, XSS protection, input filtering
- **Event System**: Extensibility through event hooks
- **Caching**: Performance optimization layer
- **Multi-language**: Built-in localization support

### PHP Version: 
- Minimum: PHP 7.0
- Recommended: PHP 8.x
- Current Test Environment: PHP 8.4.15

---

## Module Structure

Every Thonem module follows this standardized directory structure:

```
modules/{module_name}/
├── config/
│   ├── Module.php           # Module metadata and info
│   ├── Route.php            # URL routing configuration
│   ├── Menu.php             # Admin/backstage menu items
│   ├── Permissions.php      # User permission definitions
│   └── Composer.json        # Module-specific dependencies (optional)
│
├── controllers/
│   ├── admin/               # Admin panel controllers
│   │   └── {Module}_settings.php
│   ├── backstage/           # User management controllers
│   │   └── {Resource}.php
│   └── api/                 # API endpoints
│       └── {Module}_api.php
│
├── models/
│   └── {Resource}Model.php  # Database models
│
├── views/
│   ├── admin/               # Admin panel views
│   ├── backstage/           # User management views
│   ├── frontend/            # Public-facing views
│   └── system/              # Internal views
│
├── assets/
│   ├── css/
│   ├── js/
│   ├── img/
│   └── webfonts/
│
├── sql/
│   └── seeds/               # Database initialization
│       └── install.sql
│
├── helpers/                 # Module-specific helper functions
├── libraries/               # Module-specific classes
├── elements/                # Reusable view components
├── widgets/                 # Dashboard widgets
├── lists/                   # Dropdown/select options
├── cron/                    # Scheduled tasks
└── sockets/                 # WebSocket handlers (optional)
```

---

## Step-by-Step Module Creation

### Example: Creating a "News" Module

#### Step 1: Create Module Directory Structure

```bash
modules/news/
├── config/
├── controllers/
│   ├── admin/
│   └── backstage/
├── models/
├── views/
│   ├── admin/
│   └── backstage/
├── assets/
└── sql/seeds/
```

#### Step 2: Module Configuration (`config/Module.php`)

```php
<?php

return [
    'name' => 'News',
    'slug' => 'news',
    'version' => '1.0.0',
    'description' => 'News and articles management module',
    'author' => 'Your Name',
    'author_url' => 'https://yourwebsite.com',
    'icon' => 'ti ti-news',
    'category' => 'Content',
    'is_core' => false,
    'is_active' => true,
    'dependencies' => [], // Other modules required
];
```

#### Step 3: Database Schema (`sql/seeds/install.sql`)

```sql
CREATE TABLE IF NOT EXISTS `news_articles` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `content` TEXT,
    `excerpt` TEXT,
    `image` VARCHAR(500),
    `author_id` INT(11) UNSIGNED,
    `category_id` INT(11) UNSIGNED,
    `status` ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    `views` INT(11) DEFAULT 0,
    `published_at` DATETIME,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_author` (`author_id`),
    INDEX `idx_category` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `news_categories` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `description` TEXT,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Step 4: Model (`models/NewsModel.php`)

```php
<?php

declare(strict_types=1);

class NewsModel extends ThonemModel
{
    public $table = 'news_articles';
    public $primary_key = 'id';
    public $_list_key = 'id';
    public $_list_title = 'title';

    /**
     * Get published articles
     */
    public function getPublished(int $limit = 20, int $offset = 0): array
    {
        return $this->find([
            'where' => ['status' => 'published'],
            'order_by' => 'published_at',
            'order' => 'DESC',
            'limit' => $limit,
            'offset' => $offset
        ]);
    }

    /**
     * Get article by slug
     */
    public function getBySlug(string $slug): ?object
    {
        $results = $this->find([
            'where' => ['slug' => $slug],
            'limit' => 1
        ]);

        return $results[0] ?? null;
    }

    /**
     * Increment view count
     */
    public function incrementViews(int $id): bool
    {
        return Db::query("UPDATE {$this->table} SET views = views + 1 WHERE id = ?", [$id]);
    }

    /**
     * Get articles by category
     */
    public function getByCategory(int $categoryId, int $limit = 20): array
    {
        return $this->find([
            'where' => ['category_id' => $categoryId, 'status' => 'published'],
            'limit' => $limit
        ]);
    }
}
```

#### Step 5: Backstage Controller (`controllers/backstage/News.php`)

```php
<?php

declare(strict_types=1);

class News extends BackstageController
{
    protected $_table = 'news_articles';
    protected $_title = 'News Articles';
    protected $_model_name = 'NewsModel';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('NewsModel');
    }

    /**
     * List all articles
     */
    public function index(): void
    {
        Breadcrumbs::add('News', url('news'));

        $model = new NewsModel();
        
        // Pagination
        $perPage = 20;
        $page = (int)(get('page') ?? 1);
        $offset = ($page - 1) * $perPage;

        // Filters
        $filters = [];
        if ($status = get('status')) {
            $filters['status'] = $status;
        }

        $articles = $model->find([
            'where' => $filters,
            'order_by' => 'created_at',
            'order' => 'DESC',
            'limit' => $perPage,
            'offset' => $offset
        ]);

        $total = $model->count(['where' => $filters]);

        $this->view('news/index', [
            'articles' => $articles,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage
        ]);
    }

    /**
     * Create new article
     */
    public function create(): void
    {
        Breadcrumbs::add('News', url('news'));
        Breadcrumbs::add('Create Article', current_url());

        if (post()) {
            // Validate CSRF token
            if (!CSRF::validate(post('csrf_token'))) {
                redirect(current_url());
                exit;
            }

            // Validate input
            $validation = $this->validate([
                'title' => 'required|max:255',
                'content' => 'required',
                'status' => 'required|in:draft,published,archived'
            ]);

            if (!$validation['valid']) {
                $this->view('news/form', [
                    'errors' => $validation['errors'],
                    'article' => (object)post()
                ]);
                return;
            }

            // Prepare data
            $model = new NewsModel();
            $data = [
                'title' => Input::post('title'),
                'slug' => $this->generateSlug(Input::post('title')),
                'content' => Input::post('content'),
                'excerpt' => Input::post('excerpt'),
                'image' => Input::post('image'),
                'author_id' => current_user_id(),
                'category_id' => Input::post('category_id'),
                'status' => Input::post('status'),
                'published_at' => Input::post('status') === 'published' ? date('Y-m-d H:i:s') : null
            ];

            $id = $model->insert($data);

            if ($id) {
                Session::flash('success', 'Article created successfully');
                redirect(url('news'));
            } else {
                Session::flash('error', 'Failed to create article');
            }
        }

        $this->view('news/form', [
            'article' => null,
            'categories' => $this->getCategories()
        ]);
    }

    /**
     * Edit article
     */
    public function edit(int $id): void
    {
        Breadcrumbs::add('News', url('news'));
        Breadcrumbs::add('Edit Article', current_url());

        $model = new NewsModel();
        $article = $model->getById($id);

        if (!$article) {
            redirect(url('news'));
            exit;
        }

        if (post()) {
            if (!CSRF::validate(post('csrf_token'))) {
                redirect(current_url());
                exit;
            }

            $validation = $this->validate([
                'title' => 'required|max:255',
                'content' => 'required',
                'status' => 'required|in:draft,published,archived'
            ]);

            if (!$validation['valid']) {
                $this->view('news/form', [
                    'errors' => $validation['errors'],
                    'article' => (object)post(),
                    'categories' => $this->getCategories()
                ]);
                return;
            }

            $data = [
                'title' => Input::post('title'),
                'content' => Input::post('content'),
                'excerpt' => Input::post('excerpt'),
                'image' => Input::post('image'),
                'category_id' => Input::post('category_id'),
                'status' => Input::post('status')
            ];

            if (Input::post('status') === 'published' && $article->status !== 'published') {
                $data['published_at'] = date('Y-m-d H:i:s');
            }

            $updated = $model->update($id, $data);

            if ($updated) {
                Session::flash('success', 'Article updated successfully');
                redirect(url('news'));
            } else {
                Session::flash('error', 'Failed to update article');
            }
        }

        $this->view('news/form', [
            'article' => $article,
            'categories' => $this->getCategories()
        ]);
    }

    /**
     * Delete article
     */
    public function delete(int $id): void
    {
        if (!CSRF::validate(post('csrf_token'))) {
            Response::json(['success' => false, 'message' => 'Invalid request']);
            exit;
        }

        $model = new NewsModel();
        $deleted = $model->delete($id);

        if ($deleted) {
            Session::flash('success', 'Article deleted successfully');
            Response::json(['success' => true]);
        } else {
            Response::json(['success' => false, 'message' => 'Failed to delete article']);
        }
    }

    /**
     * Get categories for dropdown
     */
    private function getCategories(): array
    {
        $categories = Db::query("SELECT id, name FROM news_categories ORDER BY name");
        return $categories ?? [];
    }

    /**
     * Generate URL-friendly slug
     */
    private function generateSlug(string $title): string
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
        
        // Check if slug exists
        $count = 1;
        $originalSlug = $slug;
        while (Db::query("SELECT id FROM news_articles WHERE slug = ?", [$slug])) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        
        return $slug;
    }

    /**
     * Validate input
     */
    private function validate(array $rules): array
    {
        $validator = new Validator();
        return $validator->validate(post(), $rules);
    }
}
```

#### Step 6: View Template (`views/backstage/news/index.php`)

```php
<div class="page-header">
    <h1><?= $this->_title ?></h1>
    <div class="page-actions">
        <a href="<?= url('news/create') ?>" class="btn btn-primary">
            <i class="ti ti-plus"></i> Create Article
        </a>
    </div>
</div>

<?php if (Session::has('success')): ?>
    <div class="alert alert-success">
        <?= Session::flash('success') ?>
    </div>
<?php endif; ?>

<?php if (Session::has('error')): ?>
    <div class="alert alert-danger">
        <?= Session::flash('error') ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <form method="get" class="filters">
            <?= Form::select('status', [
                '' => 'All Statuses',
                'draft' => 'Draft',
                'published' => 'Published',
                'archived' => 'Archived'
            ], get('status'), ['class' => 'form-control']) ?>
            
            <button type="submit" class="btn btn-secondary">Filter</button>
        </form>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Published</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($articles)): ?>
                    <tr>
                        <td colspan="6" class="text-center">No articles found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($articles as $article): ?>
                        <tr>
                            <td><?= $article->id ?></td>
                            <td>
                                <a href="<?= url('news/edit/' . $article->id) ?>">
                                    <?= htmlspecialchars($article->title) ?>
                                </a>
                            </td>
                            <td>
                                <span class="badge badge-<?= $article->status === 'published' ? 'success' : 'warning' ?>">
                                    <?= ucfirst($article->status) ?>
                                </span>
                            </td>
                            <td><?= number_format($article->views) ?></td>
                            <td><?= $article->published_at ? date('Y-m-d', strtotime($article->published_at)) : '-' ?></td>
                            <td>
                                <a href="<?= url('news/edit/' . $article->id) ?>" class="btn btn-sm btn-info">
                                    <i class="ti ti-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteArticle(<?= $article->id ?>)">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if ($total > $perPage): ?>
        <div class="card-footer">
            <?= Pagination::render($total, $perPage, $page, url('news')) ?>
        </div>
    <?php endif; ?>
</div>

<script>
function deleteArticle(id) {
    if (!confirm('Are you sure you want to delete this article?')) {
        return;
    }
    
    fetch('<?= url("news/delete/") ?>' + id, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            csrf_token: '<?= CSRF::generate() ?>'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'Failed to delete article');
        }
    });
}
</script>
```

#### Step 7: Form View (`views/backstage/news/form.php`)

```php
<div class="page-header">
    <h1><?= isset($article) ? 'Edit Article' : 'Create Article' ?></h1>
</div>

<div class="card">
    <form method="post" action="<?= current_url() ?>">
        <?= Form::hidden('csrf_token', CSRF::generate()) ?>
        
        <div class="card-body">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-md-8">
                    <?= Form::text('title', $article->title ?? '', [
                        'label' => 'Title',
                        'required' => true,
                        'placeholder' => 'Enter article title'
                    ]) ?>
                    
                    <?= Form::rich_text('content', $article->content ?? '', [
                        'label' => 'Content',
                        'required' => true,
                        'rows' => 15
                    ]) ?>
                    
                    <?= Form::textarea('excerpt', $article->excerpt ?? '', [
                        'label' => 'Excerpt',
                        'rows' => 3,
                        'placeholder' => 'Short description or summary'
                    ]) ?>
                </div>
                
                <div class="col-md-4">
                    <?= Form::select('status', [
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived'
                    ], $article->status ?? 'draft', [
                        'label' => 'Status',
                        'required' => true
                    ]) ?>
                    
                    <?= Form::select('category_id', 
                        array_column($categories, 'name', 'id'),
                        $article->category_id ?? '',
                        ['label' => 'Category']
                    ) ?>
                    
                    <?= Form::media('image', $article->image ?? '', [
                        'label' => 'Featured Image',
                        'type' => 'image'
                    ]) ?>
                </div>
            </div>
        </div>
        
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <?= isset($article) ? 'Update Article' : 'Create Article' ?>
            </button>
            <a href="<?= url('news') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
```

#### Step 8: API Controller (`controllers/api/News_api.php`)

```php
<?php

declare(strict_types=1);

class News_api extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('NewsModel');
    }

    /**
     * GET /api/news - List published articles
     */
    public function list(): void
    {
        $model = new NewsModel();
        
        $limit = min((int)(get('limit') ?? 20), 100);
        $offset = (int)(get('offset') ?? 0);
        
        $articles = $model->getPublished($limit, $offset);
        
        Response::json([
            'success' => true,
            'data' => $articles,
            'pagination' => [
                'limit' => $limit,
                'offset' => $offset
            ]
        ]);
    }

    /**
     * GET /api/news/:slug - Get single article
     */
    public function show(string $slug): void
    {
        $model = new NewsModel();
        $article = $model->getBySlug($slug);
        
        if (!$article) {
            Response::json([
                'success' => false,
                'message' => 'Article not found'
            ], 404);
            return;
        }
        
        // Increment views
        $model->incrementViews($article->id);
        
        Response::json([
            'success' => true,
            'data' => $article
        ]);
    }

    /**
     * GET /api/news/category/:id - Get articles by category
     */
    public function byCategory(int $categoryId): void
    {
        $model = new NewsModel();
        $articles = $model->getByCategory($categoryId);
        
        Response::json([
            'success' => true,
            'data' => $articles
        ]);
    }
}
```

#### Step 9: Route Configuration (`config/Route.php`)

```php
<?php

return [
    // Backstage routes
    'news' => 'News@index',
    'news/create' => 'News@create',
    'news/edit/:id' => 'News@edit',
    'news/delete/:id' => 'News@delete',
    
    // API routes
    'api/news' => 'api/News_api@list',
    'api/news/:slug' => 'api/News_api@show',
    'api/news/category/:id' => 'api/News_api@byCategory',
];
```

#### Step 10: Menu Configuration (`config/Menu.php`)

```php
<?php

return [
    'backstage' => [
        [
            'title' => 'News',
            'url' => 'news',
            'icon' => 'ti ti-news',
            'permission' => 'news.view',
            'order' => 50
        ]
    ],
    'admin' => [
        [
            'title' => 'News Settings',
            'url' => 'admin/news/settings',
            'icon' => 'ti ti-settings',
            'permission' => 'news.settings',
            'parent' => 'settings',
            'order' => 50
        ]
    ]
];
```

#### Step 11: Permissions (`config/Permissions.php`)

```php
<?php

return [
    'news.view' => 'View news articles',
    'news.create' => 'Create news articles',
    'news.edit' => 'Edit news articles',
    'news.delete' => 'Delete news articles',
    'news.settings' => 'Manage news settings',
];
```

---

## Core Components Reference

### 1. Controllers

#### Base Controller Classes:

```php
// For user management areas
class MyController extends BackstageController

// For admin panel
class MyController extends AdminController

// For API endpoints
class MyController extends ApiController

// For frontend/public pages
class MyController extends FrontendController

// For payment processing
class MyController extends PaymentController
```

#### Common Controller Methods:

```php
// Load model
$this->load->model('ModelName');

// Render view
$this->view('module/view_name', $data);

// Redirect
redirect(url('path'));

// Get current user
$userId = current_user_id();
$user = current_user();

// Flash messages
Session::flash('success', 'Message');
Session::flash('error', 'Error message');

// Check permission
if (has_permission('module.action')) {
    // Allow
}
```

---

### 2. Models (ThonemModel)

```php
class ProductModel extends ThonemModel
{
    public $table = 'products';
    public $primary_key = 'id';
    public $_list_key = 'id';
    public $_list_title = 'name';

    // Find records
    public function findActive()
    {
        return $this->find([
            'where' => ['status' => 'active'],
            'order_by' => 'created_at',
            'order' => 'DESC',
            'limit' => 20
        ]);
    }

    // Get by ID
    public function getById(int $id)
    {
        return $this->find(['where' => ['id' => $id], 'limit' => 1])[0] ?? null;
    }

    // Insert
    public function create(array $data): int
    {
        return $this->insert($data);
    }

    // Update
    public function updateRecord(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }

    // Delete
    public function remove(int $id): bool
    {
        return $this->delete($id);
    }

    // Count
    public function countActive(): int
    {
        return $this->count(['where' => ['status' => 'active']]);
    }
}
```

---

### 3. Database Operations (Db Class)

```php
// Raw query
$results = Db::query("SELECT * FROM users WHERE status = ?", ['active']);

// Insert
$id = Db::insert('users', [
    'username' => 'john',
    'email' => 'john@example.com'
]);

// Update
$affected = Db::update('users', ['status' => 'inactive'], ['id' => 5]);

// Delete
$deleted = Db::delete('users', ['id' => 5]);

// Get single row
$user = Db::getRow("SELECT * FROM users WHERE id = ?", [5]);

// Get single value
$count = Db::getValue("SELECT COUNT(*) FROM users WHERE status = ?", ['active']);

// Transaction
Db::beginTransaction();
try {
    Db::insert('orders', $orderData);
    Db::update('products', ['stock' => 'stock - 1'], ['id' => $productId]);
    Db::commit();
} catch (Exception $e) {
    Db::rollback();
    Log::error($e->getMessage());
}
```

---

### 4. Form Generation

```php
// Text input
echo Form::text('username', $value, [
    'label' => 'Username',
    'placeholder' => 'Enter username',
    'required' => true,
    'class' => 'custom-class'
]);

// Email input
echo Form::email('email', $value, ['label' => 'Email Address']);

// Password
echo Form::password('password', '', ['label' => 'Password']);

// Textarea
echo Form::textarea('description', $value, [
    'label' => 'Description',
    'rows' => 5
]);

// Rich text editor
echo Form::rich_text('content', $value, ['label' => 'Content']);

// Select dropdown
echo Form::select('status', [
    'active' => 'Active',
    'inactive' => 'Inactive'
], $selected, ['label' => 'Status']);

// Checkbox
echo Form::checkbox('agree', '1', $checked, ['label' => 'I agree']);

// Radio
echo Form::radio('gender', 'male', $selected, ['label' => 'Male']);

// File upload
echo Form::file('attachment', ['label' => 'Upload File']);

// Media picker (images/files)
echo Form::media('image', $path, [
    'label' => 'Featured Image',
    'type' => 'image'
]);

// Date picker
echo Form::date('start_date', $value, ['label' => 'Start Date']);

// Hidden field
echo Form::hidden('csrf_token', CSRF::generate());

// Submit button
echo Form::submit('Save', ['class' => 'btn btn-primary']);
```

---

### 5. Security

```php
// CSRF Protection
// Generate token
$token = CSRF::generate();

// Validate token
if (!CSRF::validate(post('csrf_token'))) {
    redirect(current_url());
    exit;
}

// Input Filtering
// Auto XSS filtering
$name = Input::post('name');
$email = Input::post('email');

// Get without filtering
$raw = Input::post('content', false);

// Get from $_GET
$search = Input::get('q');

// Password hashing
$hashed = password('mypassword');

// Verify password
if (password_verify($inputPassword, $hashedPassword)) {
    // Correct
}

// Escape output
echo htmlspecialchars($userInput, ENT_QUOTES, 'UTF-8');
// Or use helper
echo esc($userInput);
```

---

### 6. URL and Routing

```php
// Generate URL
echo url('products/edit/5');
// Output: https://site.com/products/edit/5

// Admin URL
echo admin_url('dashboard');
// Output: https://site.com/admin/dashboard

// Current URL
$current = current_url();

// Base URL
echo base_url('assets/css/style.css');

// Redirect
redirect(url('products'));

// Redirect with message
Session::flash('success', 'Product created');
redirect(url('products'));
```

---

### 7. Session and Cookies

```php
// Set session
Session::set('user_id', 123);

// Get session
$userId = Session::get('user_id');

// Check if exists
if (Session::has('user_id')) {
    // Exists
}

// Flash message (one-time)
Session::flash('success', 'Action completed');

// Get and remove flash
if (Session::has('success')) {
    echo Session::flash('success');
}

// Destroy session
Session::destroy();

// Cookies
Cookie::set('preference', 'dark_mode', 86400); // 1 day
$pref = Cookie::get('preference');
Cookie::delete('preference');
```

---

### 8. Caching

```php
// Cache expensive operation
$data = Cache::get('products_list', function() {
    $model = new ProductModel();
    return $model->findAll();
}, 3600); // Cache for 1 hour

// Set cache manually
Cache::set('key', $value, 3600);

// Get cache
$value = Cache::get('key');

// Delete cache
Cache::delete('key');

// Clear all cache
Cache::clear();
```

---

### 9. Event System

```php
// Set event hook (in controller/view)
Event::set('BEFORE_HEAD_CLOSE', '<script src="custom.js"></script>');

// Append to existing events
Event::append('BEFORE_HEAD_CLOSE', '<link rel="stylesheet" href="custom.css">');

// Get event output (in layout)
<?= Event::get('BEFORE_HEAD_CLOSE') ?>

// Common event hooks:
// - BEFORE_HEAD_CLOSE
// - AFTER_BODY_OPEN
// - BEFORE_BODY_CLOSE
// - BEFORE_CONTENT
// - AFTER_CONTENT
```

---

### 10. File Upload

```php
// Handle file upload
$upload = new Upload();

$upload->setConfig([
    'upload_path' => 'uploads/images/',
    'allowed_types' => 'jpg|jpeg|png|gif',
    'max_size' => 5120, // 5MB in KB
    'max_width' => 2000,
    'max_height' => 2000,
    'encrypt_name' => true
]);

if ($upload->do_upload('image')) {
    $fileData = $upload->data();
    $filePath = $fileData['file_path'];
} else {
    $errors = $upload->errors();
}
```

---

### 11. Validation

```php
// Validate input
$validator = new Validator();

$rules = [
    'email' => 'required|email',
    'username' => 'required|min:3|max:20|alpha_numeric',
    'password' => 'required|min:8',
    'age' => 'required|numeric|min_value:18',
    'website' => 'url',
    'terms' => 'required|accepted'
];

$result = $validator->validate($_POST, $rules);

if ($result['valid']) {
    // Validation passed
} else {
    $errors = $result['errors'];
}

// Available validation rules:
// required, email, url, numeric, alpha, alpha_numeric, 
// min:n, max:n, min_value:n, max_value:n, 
// in:value1,value2, regex:pattern, accepted
```

---

### 12. Logging

```php
// Log error
Log::error('Database connection failed: ' . $error);

// Log info
Log::info('User logged in: ' . $userId);

// Log warning
Log::warning('Low disk space');

// Log debug
Log::debug('Debug info', ['data' => $debugData]);
```

---

### 13. Breadcrumbs

```php
// Add breadcrumb
Breadcrumbs::add('Home', url(''));
Breadcrumbs::add('Products', url('products'));
Breadcrumbs::add('Edit Product', current_url());

// Render breadcrumbs (in view)
<?= Breadcrumbs::render() ?>
```

---

### 14. Pagination

```php
// In controller
$total = 150;
$perPage = 20;
$currentPage = (int)(get('page') ?? 1);

// In view
<?= Pagination::render($total, $perPage, $currentPage, url('products')) ?>
```

---

### 15. Response Helpers

```php
// JSON response
Response::json([
    'success' => true,
    'data' => $data
]);

// JSON with status code
Response::json(['error' => 'Not found'], 404);

// Download file
Response::download('/path/to/file.pdf', 'custom-name.pdf');

// Set header
Response::header('Content-Type', 'application/xml');
```

---

## Best Practices

### DO:
1. **Always extend base controllers**: BackstageController, AdminController, ApiController
2. **Use ThonemModel**: Never write raw SQL in controllers
3. **Use Form:: methods**: Never write raw HTML inputs
4. **Validate CSRF tokens**: On all POST/PUT/DELETE requests
5. **Use Input:: class**: For all user input (auto XSS filtering)
6. **Escape output**: Use `htmlspecialchars()` or `esc()` for user data
7. **Use url() function**: For generating URLs
8. **Cache expensive operations**: Database queries, API calls
9. **Follow naming conventions**: PascalCase for classes, snake_case for files
10. **Add breadcrumbs**: For better navigation
11. **Use transactions**: For multi-table operations
12. **Log errors**: Use Log:: class for debugging
13. **Validate permissions**: Check user permissions before actions
14. **Use prepared statements**: Always use parameter binding

### DON'T:
1. **Never use eval()**: Security risk
2. **Never hardcode credentials**: Use environment variables
3. **Never access $_POST/$_GET directly**: Use Input:: class
4. **Never suppress errors with @**: Use proper error handling
5. **Never use mysql_* functions**: Use PDO or Db:: class
6. **Never trust user input**: Always validate and sanitize
7. **Never expose stack traces**: In production
8. **Never use unserialize()**: On untrusted data
9. **Never commit sensitive data**: To version control
10. **Never skip CSRF validation**: On forms

---

## Common Patterns

### Pattern 1: CRUD Controller

```php
class ResourceController extends BackstageController
{
    protected $_table = 'resources';
    protected $_model_name = 'ResourceModel';

    public function index() { /* list */ }
    public function create() { /* create form */ }
    public function store() { /* save new */ }
    public function edit($id) { /* edit form */ }
    public function update($id) { /* save changes */ }
    public function delete($id) { /* remove */ }
}
```

### Pattern 2: API Response

```php
public function apiMethod(): void
{
    try {
        $data = $this->processData();
        
        Response::json([
            'success' => true,
            'data' => $data,
            'message' => 'Operation successful'
        ]);
    } catch (Exception $e) {
        Log::error($e->getMessage());
        
        Response::json([
            'success' => false,
            'message' => 'An error occurred'
        ], 500);
    }
}
```

### Pattern 3: Form Validation

```php
if (post()) {
    if (!CSRF::validate(post('csrf_token'))) {
        redirect(current_url());
        exit;
    }

    $validation = $this->validate($rules);
    
    if (!$validation['valid']) {
        $this->view('form', [
            'errors' => $validation['errors'],
            'data' => (object)post()
        ]);
        return;
    }

    // Process valid data
}
```

---

## Testing

### Manual Testing Checklist:

1. **Functionality**:
   - [ ] All CRUD operations work
   - [ ] Forms validate correctly
   - [ ] API endpoints return correct data
   - [ ] Error handling works

2. **Security**:
   - [ ] CSRF tokens validated
   - [ ] Input sanitized
   - [ ] Output escaped
   - [ ] SQL injection prevented
   - [ ] XSS protection active

3. **User Experience**:
   - [ ] Success/error messages display
   - [ ] Breadcrumbs show correctly
   - [ ] Pagination works
   - [ ] Forms pre-populate on edit
   - [ ] Navigation menu appears

4. **Performance**:
   - [ ] Database queries optimized
   - [ ] Caching implemented
   - [ ] No N+1 query problems
   - [ ] Page load times acceptable

---

## Module Installation

### Steps for AI to Generate Installation Instructions:

1. **Database**: Run SQL file from `sql/seeds/install.sql`
2. **Composer**: If module has dependencies, run `composer update`
3. **Permissions**: Grant permissions to users/roles
4. **Configuration**: Set module config in admin panel
5. **Activation**: Enable module from admin > modules

---

## Quick Module Template Prompt for AI

```
Create a Thonem module named [MODULE_NAME] with the following features:

1. Database: [DESCRIBE TABLES]
2. CRUD Operations: [LIST OPERATIONS]
3. API Endpoints: [LIST ENDPOINTS]
4. Features: [SPECIAL FEATURES]

Follow Thonem best practices:
- Extend BackstageController for management
- Use ThonemModel for database
- Implement CSRF protection
- Use Form:: methods
- Add breadcrumbs
- Include validation
- Cache expensive queries
- Follow naming conventions
```

---

## Additional Resources

- **Framework Docs**: https://docs.thonem.com
- **Repository**: https://github.com/thonem/framework
- **Support**: support@thonem.com

---

**Generated:** 2025-12-18  
**For:** AI Assistants building Thonem modules  
**Version:** 1.0.0
