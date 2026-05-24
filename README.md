# CodeIgniter Seeder for PHP Database Seeding

[![Packagist Version](https://img.shields.io/packagist/v/fishingboy/codeigniter-seeder.svg)](https://packagist.org/packages/fishingboy/codeigniter-seeder)
[![Downloads](https://img.shields.io/packagist/dt/fishingboy/codeigniter-seeder.svg?label=Downloads)](https://packagist.org/packages/fishingboy/codeigniter-seeder)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

A small CodeIgniter seeder package for creating repeatable database seed scripts that can be listed and executed from the command line.

## Language

[en-us](README.md) /
[zh-tw](README-zh-tw.md)

## Features

- Add database seeders to a CodeIgniter project without building a custom runner.
- Run all seeders or execute one specific seeder by class name.
- Control execution order with a simple `$priority` property.
- List available seeders and their priority from the CLI.
- Use the normal CodeIgniter instance inside seeders through `$this->CI`.
- Restrict browser access to local, private network, or development environments.

## Why This Package?

CodeIgniter does not include a Laravel-style database seeder workflow by default. This package provides a lightweight seeder controller and base class so existing CodeIgniter applications can seed development data, lookup tables, roles, settings, and demo records with predictable CLI commands.

It is intentionally small: seed files are plain PHP classes, database writes use CodeIgniter's existing database layer, and the runner can be added to an existing project with minimal setup.

## Installation

```shell
composer require fishingboy/codeigniter-seeder
```

## Quick Start

### 1. Create the seeder controller

Create `application/controllers/Seeder.php`:

```php
<?php

use fishingboy\ci_seeder\CI_Seeder_Controller;

class Seeder extends CI_Seeder_Controller
{
}
```

### 2. Create the seeders directory

Create this directory in your CodeIgniter application:

```text
application/seeders
```

### 3. Create a sample seeder

Create `application/seeders/Sample_seeder.php`:

```php
<?php

use fishingboy\ci_seeder\CI_Seeder_base;

class Sample_seeder extends CI_Seeder_base
{
    /**
     * Higher priority seeders run first.
     *
     * @var integer
     */
    public $priority = 100;

    /**
     * Insert seed data.
     *
     * @return integer Number of inserted rows.
     */
    public function run()
    {
        $this->CI->db->insert("users", [
            'name' => 'fishingboy',
        ]);

        return 1;
    }
}
```

### 4. Run the seeder

```shell
php index.php seeder run Sample_seeder
```

Example output:

```text
Seed [Sample_seeder] complete, carete 1 rows.
```

## Commands

```shell
php index.php seeder                   # Show help and seeder list
php index.php seeder run               # Execute all seeders
php index.php seeder run {seeder_name} # Execute one seeder
php index.php seeder ls                # List seeder status
```

Example list output:

```text
php index.php seeder run Sample_seeder                     (priority: 100)
```

## Real World Example

Use seeders for stable data that must exist across local, staging, or test environments.

```php
<?php

use fishingboy\ci_seeder\CI_Seeder_base;

class Role_seeder extends CI_Seeder_base
{
    public $priority = 200;

    public function run()
    {
        $roles = [
            ['name' => 'admin'],
            ['name' => 'editor'],
            ['name' => 'member'],
        ];

        foreach ($roles as $role) {
            $this->CI->db->insert('roles', $role);
        }

        return count($roles);
    }
}
```

Run it with:

```shell
php index.php seeder run Role_seeder
```

## Seeder Naming Rules

- Seeder files must be placed in `application/seeders`.
- Seeder class names must end with `_seeder`.
- Seeder file names must match the class name, for example `Sample_seeder.php`.
- Seeders should extend `fishingboy\ci_seeder\CI_Seeder_base`.
- Higher `$priority` values run before lower values.

## Comparison

| Approach | Best for | Trade-off |
| --- | --- | --- |
| Codeigniter-Seeder | Repeatable seed data in existing CodeIgniter projects | Requires adding a seeder controller |
| CodeIgniter migrations | Schema changes and database versioning | Not focused on data seeding |
| One-off SQL files | Manual imports or production handoff | Harder to reuse, order, and review in PHP code |
| Custom CLI scripts | Highly specific project workflows | More code to maintain |

## FAQ

### Does this package replace CodeIgniter migrations?

No. Migrations are better for schema changes. This package is for inserting or preparing data such as users, roles, permissions, settings, lookup tables, and demo records.

### Can I run only one seeder?

Yes. Pass the seeder class name:

```shell
php index.php seeder run Sample_seeder
```

### How is execution order controlled?

Set the public `$priority` property on each seeder. Higher values run first.

### Can seeders use CodeIgniter models, libraries, and the database object?

Yes. Seeders have access to the CodeIgniter instance through `$this->CI`, so you can use `$this->CI->db`, load models, or call existing application services.

### Is browser access allowed?

The controller is intended for CLI usage. Browser access is limited to local, private network, or `development` environments by the controller.

## Keywords

CodeIgniter seeder, CodeIgniter database seeding, PHP seeder, CodeIgniter seed data, CodeIgniter CLI seeder, database fixtures, PHP database seed script.

## License

CodeIgniter-Seeder is open-sourced software licensed under the [MIT license](LICENSE).
