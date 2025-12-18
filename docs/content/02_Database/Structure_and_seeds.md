# Framework Database Structure and Seeds Documentation

## Overview
This documentation explains how to define table structures and seed data for the framework's database layer. The framework provides a comprehensive Db class with methods for creating, modifying, and seeding database tables.

## Table Structure Definition
 
### JSON Structure Format
Tables are defined using a JSON structure with the following format:

```json
{
"name": "table_name",
"fields": [
    {
    "name": "column_name",
    "type": "data_type",
    "max_length": integer_or_null,
    "default": "default_value",
    "primary_key": boolean_or_int,
    "auto_increment": boolean,
    "null": boolean,
    "unsigned": boolean,
    "comment": "column comment"
    },
    // ... more fields
],
"indexes": [
    {
    "name": "index_name",
    "columns": ["column1", "column2"],
    "unique": boolean
    }
],
"foreign_keys": [
    {
    "name": "fk_name",
    "column": "local_column",
    "ref_table": "referenced_table",
    "ref_column": "referenced_column",
    "on_delete": "CASCADE|SET NULL|RESTRICT|NO ACTION",
    "on_update": "CASCADE|SET NULL|RESTRICT|NO ACTION"
    }
]
}
```
## Example Table Structure
```json
{
"name": "banned_ips",
"fields": [
    {
        "name": "banned_ip_id",
        "type": "int",
        "max_length": 11,
        "default": null,
        "primary_key": 1,
        "auto_increment": true,
        "null": false
    },
    {
        "name": "ip",
        "type": "varchar",
        "max_length": 45,
        "default": null,
        "null": false
    },
    {
        "name": "reason",
        "type": "text",
        "max_length": null,
        "default": null,
        "null": true
    },
    {
        "name": "created",
        "type": "datetime",
        "max_length": null,
        "default": "CURRENT_TIMESTAMP",
        "null": false
    },
    {
        "name": "updated",
        "type": "datetime",
        "max_length": null,
        "default": "CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
        "null": false
    }
],
"indexes": [
    {
    "name": "idx_ip",
    "columns": ["ip"],
    "unique": false
    }
]
}
```
## Supported Data Types


|Type	|Description	|Max Length Required	|Example|
|:-|:-|:-|:-|
|int	|Integer	|Yes (e.g., 11)	|"type": "int", "max_length": 11|
|varchar	|Variable string	|Yes	|"type": "varchar", "max_length": 255|
text	|Long text	|No	|"type": "text"|
datetime	|Date and time	|No	|"type": "datetime"|
timestamp	|Timestamp	|No	|"type": "timestamp"|
decimal	|Decimal number	|Yes	|"type": "decimal", "max_length": "10,2"|
boolean/tinyint	|Boolean	|No	|"type": "tinyint", "max_length": 1|
json	|JSON data	|No	|"type": "json"|
enum	|Enumeration	|Yes	|"type": "enum", "max_length": "'value1','value2'"|

# Seed Data
## Defining Seed Data
Seed data can be defined in JSON or PHP array format:

```json
{
"table": "banned_ips",
"data": [
    {
    "ip": "192.168.1.1",
    "reason": "Malicious activity detected",
    "created": "2024-01-01 10:00:00"
    },
    {
    "ip": "10.0.0.5",
    "reason": "Brute force attack",
    "created": "2024-01-02 14:30:00"
    }
]
}
```