

# Validation

A comprehensive validation library inspired by Laravel/CodeIgniter.

Basic Usage

```php

$data = $_POST;

$config = [
    [
        'name' => 'email',
        'label' => 'Email Address',
        'rules' => 'required|email|unique[table=users,field=email]'
    ],
    [
        'name' => 'password',
        'label' => 'Password',
        'rules' => 'required|min:6|confirmed'
    ]
];

if (Validator::run($config, [], [], $data)) {
    // Validation Passed
} else {
    // Validation Failed
    echo Validator::$displayErrors; 
    // Or get raw errors
    // $errors = Validator::errors(); 
}
```

## Available Rules

| Rule                    | 	Parameter  Example   | 	Description                                                                                                                                                                                                             |
|:------------------------|:----------------------|:-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| required                | 	-	                   | Value                     cannot be empty.                                                                                                                                                                               |
| email                   | 	-                    | 	Must be valid email.                                                                                                                                                                                                    |
| min                     | 	min:5                | 	Minimum length or value.                                                                                                                                                                                                |
| max                     | 	max:100	             | Maximum length or value.                                                                                                                                                                                                 |
| confirmed               | 	-                    | 	Checks for field_confirmation match.                                                                                                                                                                                    |
| unique                  | 	unique[table=users]	 | Checks DB for duplicates.                                                                                                                                                                                                |
| numeric                 | 	-                    | 	Must be a number.                                                                                                                                                                                                       |
| matches                 | 	matches:password     | 	Must match another field.                                                                                                                                                                                               |
| callback_| 	callback_myFunc      | 	Calls a custom function.                                                                                                                                                                                                |
