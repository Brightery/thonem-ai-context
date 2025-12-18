# Form Generator

The `Form` class allows you to create complex HTML forms using simple PHP methods. It handles labels, classes, error states, and specialized inputs (like Media uploads and Rich Text).

## Configuration
You can override default classes (Bootstrap style by default) in your boot process.

```php
Form::config([
    'default_class' => 'form-input',
    'error_class' => 'text-red-500',
    'media_upload_url' => '/api/media/upload'
]);
```

Standard Inputs

Text & Email
```php
echo Form::text('username', $userData->username, [
    'label' => 'Username',
    'placeholder' => 'Enter your name',
    'required' => true
]);

echo Form::email('email', '', ['label' => 'Email Address']);
```

Select Dropdown
```php

$options = ['active' => 'Active', 'banned' => 'Banned'];
echo Form::select('status', $options, 'active', [
'label' => 'User Status',
'class' => 'form-select'
]);
```

Checkbox & Radio
```php

echo Form::checkbox('agree', 1, false, ['label' => 'I agree to terms']);
echo Form::radio('gender', 'male', true, ['label' => 'Male']);
```
```php

echo Form::checkbox('agree', 1, false, ['label' => 'I agree to terms']);
echo Form::radio('gender', 'male', true, ['label' => 'Male']);
```

Advanced Inputs

Media Uploader

Generates a UI with Preview, Browse, and Upload buttons.

```php

echo Form::media('profile_pic', $imagePath, [
    'label' => 'Profile Photo',
    'media_multiple' => false,
    'media_types' => 'image/*'
]);
```

Rich Text Editor


```php
echo Form::rich_text('bio', $content, [
    'label' => 'Biography',
    'rows' => 10
]);
```
Autocomplete

```php

echo Form::autocomplete('city', '', [
    'label' => 'City',
    'autocomplete_source' => '/api/cities', // Endpoint returning JSON
    'autocomplete_min_length' => 3
]);
```



