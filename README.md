# Thonem Framework

> **Enterprise-grade modular PHP framework for building scalable e-commerce and business applications**

[![PHP Version](https://img.shields.io/badge/PHP-8.x-blue.svg)](https://php.net)
[![Framework Version](https://img.shields.io/badge/version-6.0.0-green.svg)](https://thonem.com)
[![License](https://img.shields.io/badge/license-MIT-orange.svg)](LICENSE)
[![Documentation](https://img.shields.io/badge/docs-latest-brightgreen.svg)](https://docs.thonem.com)

---

## ✨ Features

- **🧩 Modular Architecture** - 45+ pre-built modules for rapid development
- **🔒 Security-First** - Built-in CSRF protection, XSS filtering, input sanitization
- **⚡ High Performance** - Advanced caching layer, optimized queries
- **🎨 Form Builder** - Dynamic form generation with validation
- **📊 Custom ORM** - ThonemModel with query builder and relationships
- **🌍 Multi-language** - Complete i18n/l10n support
- **🔌 Event System** - Extensible architecture with event hooks
- **🚀 RESTful API** - Built-in API controller support
- **📱 WebSocket Ready** - Real-time communication support
- **👥 Multi-tenant** - Enterprise-ready multi-tenancy

---

## 🚀 Quick Start

### Installation

```bash
composer create-project thonem/framework myapp
cd myapp
php -S localhost:8000
```

Visit **http://localhost:8000** - Your app is running! 🎉

### Hello World Controller

```php
<?php

declare(strict_types=1);

class HelloController extends FrontendController
{
    public function index(): void
    {
        $this->view('hello', ['message' => 'Hello, Thonem!']);
    }
}
```

### Hello World Model

```php
<?php

declare(strict_types=1);

class ProductModel extends ThonemModel
{
    public $table = 'products';
    public $primary_key = 'id';

    public function getActive(): array
    {
        return $this->find(['where' => ['status' => 'active']]);
    }
}
```

---

## 📚 Documentation

| Resource | Link |
|----------|------|
| 📖 Full Documentation | [docs.thonem.com](https://docs.thonem.com) |
| ⚡ Quick Start Guide | [Getting Started](https://docs.thonem.com/quick-start) |
| 🔧 Installation Guide | [Installation](https://docs.thonem.com/installation) |
| 🏗️ Architecture Overview | [Architecture](https://docs.thonem.com/architecture) |
| 📦 Module Development | [AI_MODULE_BUILDER_GUIDE.md](AI_MODULE_BUILDER_GUIDE.md) |
| 🔐 Security Best Practices | [Security Guide](https://docs.thonem.com/security) |
| 🎯 API Reference | [API Docs](https://docs.thonem.com/api) |

---

## 🏗️ Architecture

Thonem follows a **modular MVC architecture** where each module is self-contained:

```
modules/{module_name}/
├── config/              # Module configuration
├── controllers/         # MVC Controllers
│   ├── admin/          # Admin panel controllers
│   ├── backstage/      # User management controllers
│   └── api/            # API endpoints
├── models/             # Database models
├── views/              # Templates
├── assets/             # CSS, JS, images
├── sql/seeds/          # Database seeds
└── helpers/            # Utility functions
```

### Base Controllers

```php
// For user management areas
class MyController extends BackstageController

// For admin panel
class MyController extends AdminController

// For API endpoints
class MyController extends ApiController

// For public pages
class MyController extends FrontendController
```

---

## 🎯 Core Components

### Form Builder

```php
<?= Form::text('username', $value, ['label' => 'Username', 'required' => true]) ?>
<?= Form::email('email', $value, ['label' => 'Email Address']) ?>
<?= Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], $selected) ?>
<?= Form::media('image', $path, ['label' => 'Upload Image']) ?>
<?= Form::rich_text('content', $value, ['label' => 'Content']) ?>
<?= Form::hidden('csrf_token', CSRF::generate()) ?>
```

### Database Operations

```php
// Using ThonemModel
$model = new ProductModel();

// Find records
$products = $model->find([
    'where' => ['status' => 'active'],
    'order_by' => 'created_at',
    'order' => 'DESC',
    'limit' => 20
]);

// Insert
$id = $model->insert(['name' => 'Product Name', 'price' => 99.99]);

// Update
$model->update($id, ['price' => 89.99]);

// Delete
$model->delete($id);
```

### Security

```php
// CSRF Protection
if (!CSRF::validate(post('csrf_token'))) {
    redirect(current_url());
}

// Input Filtering (auto XSS protection)
$name = Input::post('name');
$email = Input::get('email');

// Output Escaping
echo htmlspecialchars($userInput, ENT_QUOTES, 'UTF-8');
// or
echo esc($userInput);
```

### Caching

```php
// Cache expensive operations
$data = Cache::get('products_list', function() {
    $model = new ProductModel();
    return $model->findAll();
}, 3600); // Cache for 1 hour
```

### Routing

```php
// config/Route.php
return [
    'products' => 'ProductController@index',
    'products/create' => 'ProductController@create',
    'products/edit/:id' => 'ProductController@edit',
    'api/products' => 'api/ProductApi@list',
];
```

---

## 📦 Built-in Modules

Thonem comes with **45+ pre-built modules**:

| Category | Modules |
|----------|---------|
| **E-commerce** | ecommerce, payments, pos, ecommerce_app |
| **Business** | crm, hrm, accounting, marketing, realestate |
| **Communication** | chat, mailbox, newsletters, support |
| **Content** | blog, documentation, form_builder |
| **Utilities** | qrcodes, oauth, tools, developer_tools |
| **Apps** | delivery_app, reseller_app, kitchen_manager, taxi |
| **AI/Automation** | ai, bot, auto |

---

## 🔥 Example: Complete CRUD Module

### Controller (`controllers/backstage/Products.php`)

```php
<?php

declare(strict_types=1);

class Products extends BackstageController
{
    protected $_table = 'products';
    protected $_title = 'Products';

    public function index(): void
    {
        Breadcrumbs::add('Products', url('products'));
        
        $model = new ProductModel();
        $products = $model->find(['limit' => 20]);
        
        $this->view('products/index', ['products' => $products]);
    }

    public function create(): void
    {
        if (post()) {
            if (!CSRF::validate(post('csrf_token'))) {
                redirect(current_url());
            }

            $model = new ProductModel();
            $id = $model->insert([
                'name' => Input::post('name'),
                'price' => Input::post('price'),
                'status' => Input::post('status')
            ]);

            Session::flash('success', 'Product created successfully');
            redirect(url('products'));
        }

        $this->view('products/form');
    }
}
```

### Model (`models/ProductModel.php`)

```php
<?php

declare(strict_types=1);

class ProductModel extends ThonemModel
{
    public $table = 'products';
    public $primary_key = 'id';
    public $_list_key = 'id';
    public $_list_title = 'name';

    public function getActive(): array
    {
        return $this->find([
            'where' => ['status' => 'active'],
            'order_by' => 'created_at',
            'order' => 'DESC'
        ]);
    }
}
```

### API (`controllers/api/Products_api.php`)

```php
<?php

declare(strict_types=1);

class Products_api extends ApiController
{
    public function list(): void
    {
        $model = new ProductModel();
        $products = $model->getActive();

        Response::json([
            'success' => true,
            'data' => $products
        ]);
    }
}
```

---

## 🤖 AI Assistant Integration

Thonem is optimized for AI-assisted development:

### Model Context Protocol (MCP)

```bash
# Install MCP server
npm install -g thonem-mcp-server

# Configure Claude Desktop
# Add to ~/Library/Application Support/Claude/claude_desktop_config.json:
{
  "mcpServers": {
    "thonem": {
      "command": "thonem-mcp-server"
    }
  }
}
```

### AI Code Generation

Ask your AI assistant:
- "Generate a Thonem controller for blog posts"
- "Create a Thonem model for products with categories"
- "Build a complete CRUD module for inventory management"

See **[AI_MODULE_BUILDER_GUIDE.md](AI_MODULE_BUILDER_GUIDE.md)** for complete AI integration guide.

---

## 🌟 Why Thonem?

| Feature | Thonem | Laravel | Symfony | CodeIgniter |
|---------|--------|---------|---------|-------------|
| Built-in Modules | ✅ 45+ | ❌ Packages | ❌ Bundles | ❌ Libraries |
| Form Builder | ✅ Advanced | ✅ Basic | ✅ Advanced | ❌ Manual |
| Modular by Design | ✅ Core | ❌ Optional | ⚠️ Bundles | ❌ Manual |
| Learning Curve | 🟢 Easy | 🟡 Moderate | 🔴 Steep | 🟢 Easy |
| Enterprise Ready | ✅ Yes | ✅ Yes | ✅ Yes | ⚠️ Limited |
| E-commerce Focus | ✅ Yes | ❌ No | ❌ No | ❌ No |
| PHP 8+ Support | ✅ Yes | ✅ Yes | ✅ Yes | ⚠️ Partial |

---

## 🛡️ Security

Thonem takes security seriously:

- ✅ CSRF token protection
- ✅ XSS filtering on all inputs
- ✅ SQL injection prevention (prepared statements)
- ✅ Password hashing (bcrypt)
- ✅ Input sanitization
- ✅ Role-based access control (RBAC)
- ✅ Security audits and updates

See **[BUGS_AND_SECURITY_REPORT.md](BUGS_AND_SECURITY_REPORT.md)** for security audit results.

---

## 📊 Performance

Optimized for production:

- ⚡ Advanced caching layer
- ⚡ Query optimization
- ⚡ Lazy loading
- ⚡ Asset minification
- ⚡ Database connection pooling

---

## 🤝 Community

| Platform | Link |
|----------|------|
| 💬 Discord | [discord.gg/thonem](https://discord.gg/thonem) |
| 💡 Forum | [forum.thonem.com](https://forum.thonem.com) |
| 📚 Stack Overflow | [stackoverflow.com/questions/tagged/thonem](https://stackoverflow.com/questions/tagged/thonem) |
| 🐦 Twitter/X | [@ThonemFramework](https://twitter.com/thoneframework) |
| 📧 Email | support@thonem.com |

---

## 🚀 Getting Started

### Requirements

- PHP 8.0 or higher
- MySQL 5.7+ or MariaDB 10.2+
- Composer
- Apache/Nginx with mod_rewrite

### Installation Steps

1. **Install via Composer**
   ```bash
   composer create-project thonem/framework myapp
   cd myapp
   ```

2. **Configure Database**
   ```bash
   cp .env.example .env
   # Edit .env with your database credentials
   ```

3. **Run Migration**
   ```bash
   php thonemcli migrate
   ```

4. **Start Development Server**
   ```bash
   php -S localhost:8000
   ```

5. **Access Application**
   - Frontend: http://localhost:8000
   - Admin: http://localhost:8000/admin

---

## 📖 Learning Resources

### Tutorials
- [Building Your First Thonem App](https://thonem.com/tutorials/first-app)
- [Creating Custom Modules](https://thonem.com/tutorials/custom-modules)
- [REST API Development](https://thonem.com/tutorials/rest-api)

### Video Courses
- [Thonem Fundamentals (YouTube)](https://youtube.com/thonem)
- [E-commerce with Thonem](https://thonem.com/courses/ecommerce)

### Books
- [Mastering Thonem Framework](https://thonem.com/book)

---

## 🤝 Contributing

We welcome contributions! See [CONTRIBUTING.md](CONTRIBUTING.md) for guidelines.

### Development

```bash
# Clone repository
git clone https://github.com/thonem/framework.git
cd framework

# Install dependencies
composer install

# Run tests
./vendor/bin/phpunit
```

---

## 📝 License

Thonem Framework is open-sourced software licensed under the [MIT license](LICENSE).

---

## 🙏 Credits

Created and maintained by the **Thonem Team** and [contributors](https://github.com/thonem/framework/graphs/contributors).

Special thanks to all our community members and sponsors!

---

## 🔗 Links

- **Website**: https://thonem.com
- **Documentation**: https://docs.thonem.com
- **GitHub**: https://github.com/thonem/framework
- **Packagist**: https://packagist.org/packages/thonem/framework
- **Blog**: https://thonem.com/blog
- **Examples**: https://github.com/thonem/examples

---

## 📈 Status

![GitHub Stars](https://img.shields.io/github/stars/thonem/framework?style=social)
![GitHub Forks](https://img.shields.io/github/forks/thonem/framework?style=social)
![GitHub Issues](https://img.shields.io/github/issues/thonem/framework)
![GitHub Pull Requests](https://img.shields.io/github/issues-pr/thonem/framework)
![Build Status](https://img.shields.io/github/actions/workflow/status/thonem/framework/tests.yml)
![Code Coverage](https://img.shields.io/codecov/c/github/thonem/framework)

---

<p align="center">
  <strong>Built with ❤️ by Brightery, q2</strong>
</p>

<p align="center">
  <a href="https://thonem.com">Website</a> •
  <a href="https://docs.thonem.com">Docs</a> •
  <a href="https://github.com/thonem/framework/issues">Issues</a> •
</p>
