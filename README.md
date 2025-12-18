# Thonem Project Guidelines

This document provides guidelines and information for developers working on the Thonem project.

## Build/Configuration Instructions

### Prerequisites
- PHP 7.1 or higher (PHP 8.x recommended)
- MySQL/MariaDB database
- Composer for dependency management

### Installation Steps

1. **Clone the repository**:
   ```bash
   git clone <repository-url>
   cd thonem.com
   ```

2. **Install dependencies**:
   ```bash
   composer install
   ```

3. **Configure the application**:
    - Copy or rename the `.config` file if it doesn't exist
    - Update the configuration settings in the `.config` file:
      ```
      URL=https://your-domain.com/
      DEBUG=true (set to false in production)
      DATABASE_HOSTNAME=localhost
      DATABASE_USERNAME=your_username
      DATABASE_PASSWORD=your_password
      DATABASE_NAME=your_database
      DATABASE_DRIVER=mysql
      DATABASE_CHARSET=utf8
      ```

4. **Set up the database**:
    - Create a database with the name specified in your `.config` file
    - Import the database schema (if available)

5. **Set proper permissions**:
    - Ensure the `storage` directory is writable by the web server

6. **Configure web server**:
    - Point your web server's document root to the project's root directory
    - Ensure all requests are directed to `index.php` (URL rewriting)

## Testing Information

### Testing Framework

The project doesn't have a standardized testing framework set up. However, you can implement tests using PHPUnit or a simple custom testing approach.

### Setting Up PHPUnit (Recommended)

1. **Install PHPUnit as a development dependency**:
   ```bash
   composer require --dev phpunit/phpunit
   ```

2. **Create a PHPUnit configuration file** (`phpunit.xml`) in the project root:
   ```xml
   <?xml version="1.0" encoding="UTF-8"?>
   <phpunit bootstrap="vendor/autoload.php"
            colors="true"
            verbose="true">
       <testsuites>
           <testsuite name="Thonem Test Suite">
               <directory>tests</directory>
           </testsuite>
       </testsuites>
   </phpunit>
   ```

3. **Run tests**:
   ```bash
   ./vendor/bin/phpunit
   ```

### Custom Testing Approach

If you prefer a simpler approach without PHPUnit, you can create custom test scripts:

1. **Create a test directory**:
   ```bash
   mkdir -p tests
   ```

2. **Create test files** with a consistent naming convention (e.g., `*Test.php`).

3. **Example test file structure**:
   ```php
   <?php
   // tests/SimpleTest.php
   class SimpleTest {
       private $testsPassed = 0;
       private $testsFailed = 0;
       
       public function run() {
           echo "Running tests...\n";
           
           $this->testSomeFunctionality();
           
           echo "Tests completed: " . ($this->testsPassed + $this->testsFailed) . " total, " . 
                $this->testsPassed . " passed, " . $this->testsFailed . " failed\n";
           
           return $this->testsFailed === 0;
       }
       
       private function testSomeFunctionality() {
           // Test implementation
           $result = true; // Replace with actual test
           
           if ($result) {
               echo "✓ Test passed\n";
               $this->testsPassed++;
           } else {
               echo "✗ Test failed\n";
               $this->testsFailed++;
           }
       }
   }
   
   // Run the tests
   $tester = new SimpleTest();
   $result = $tester->run();
   
   // Exit with appropriate status code
   exit($result ? 0 : 1);
   ```

4. **Run tests**:
   ```bash
   php tests/SimpleTest.php
   ```

### Guidelines for Adding Tests

1. **Organize tests by component**:
    - Create separate test files for different components or features
    - Use a consistent naming convention (e.g., `ComponentNameTest.php`)

2. **Test isolation**:
    - Each test should be independent and not rely on the state from other tests
    - Reset any modified state after each test

3. **Test coverage**:
    - Aim to test both normal operation and edge cases
    - Include tests for error conditions

4. **Framework-specific testing**:
    - When testing framework components, you may need to mock or stub dependencies
    - For components that require the framework to be initialized, create a minimal bootstrap file

## Additional Development Information

### Code Style

- Follow PSR-1 and PSR-2 coding standards
- Use meaningful variable and function names
- Add comments for complex logic
- Keep functions and methods focused on a single responsibility

### Project Structure

- `framework/`: Core framework files
- `modules/`: Application modules
- `styles/`: Themes and frontend assets
- `vendor/`: Composer dependencies
- `storage/`: Logs, cache, and other runtime data
- `.config`: Application configuration

### Debugging

1. **Enable debug mode**:
    - Set `DEBUG=true` in the `.config` file
    - This will display detailed error messages

2. **Log files**:
    - Check `storage/logs/` for error logs
    - The application uses Whoops for error handling in debug mode

### Common Issues

1. **Permission problems**:
    - Ensure the `storage` directory is writable by the web server

2. **Configuration errors**:
    - Verify that the `.config` file exists and contains all required settings
    - Check database connection details

3. **Dependency issues**:
    - Run `composer install` to ensure all dependencies are installed
    - Check for compatibility issues between dependencies

### Development Workflow

1. **Local development**:
    - Use a local development environment with the same PHP version as production
    - Set `DEBUG=true` for detailed error information

2. **Version control**:
    - Commit changes with clear, descriptive messages
    - Use feature branches for new features or significant changes

3. **Deployment**:
    - Set `DEBUG=false` in production
    - Run `composer install --no-dev` to exclude development dependencies
    - Clear any cache files before deployment
   
   
