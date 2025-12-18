Loader

The Loader class is responsible for the "Boot" sequence. It loads configuration files, core libraries, and user-defined helpers.

Autoloading

To load libraries automatically on every request, edit your Autoload.php config file:
PHP

// In config/Autoload.php
$config['libraries'] = ['Database', 'Session', 'Email'];
$config['helpers']   = ['Form', 'Text', 'Date'];

The loader will then instantiate these classes and attach them to the Thonem super-instance.

Folder: content/03_Helpers

Input.md

Input Handling

The Input class provides a safe wrapper around PHP superglobals ($_GET, $_POST, etc.). It automatically applies XSS filtering using the Security library.

Retrieving Data

Basic Usage

PHP

// Get $_GET['id']
$id = Input::get('id');

// Get $_POST['username']
$user = Input::post('username');

// Get Cookie
$token = Input::cookie('auth_token');

Dot Notation (Nested Arrays)

You can access deep arrays easily.
PHP

// $_POST['user']['address']['city']
$city = Input::post('user.address.city');

Filtering

By default, all input is filtered for XSS. To get raw data, pass false as the second argument.
PHP

$rawHtml = Input::post('content', false);

Setting Cookies

PHP

// Sets a cookie for 30 days
Input::cookie('theme', 'dark');

// Deletes a cookie
Input::deleteCookie('theme');