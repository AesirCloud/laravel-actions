# READ ME

---

A simple actions package for Laravel.

> **Requirements:** PHP 8.4+ and Laravel 12.

---

<p align="center">
<a href="https://github.com/aesircloud/laravel-actions/actions" target="_blank"><img src="https://img.shields.io/github/actions/workflow/status/aesircloud/laravel-actions/test.yml?branch=main&style=flat-square"/></a>
<a href="https://packagist.org/packages/aesircloud/laravel-actions" target="_blank"><img src="https://img.shields.io/packagist/v/aesircloud/laravel-actions.svg?style=flat-square"/></a>
<a href="https://packagist.org/packages/aesircloud/laravel-actions" target="_blank"><img src="https://img.shields.io/packagist/dt/aesircloud/laravel-actions.svg?style=flat-square"/></a>
<a href="https://packagist.org/packages/aesircloud/laravel-actions" target="_blank"><img src="https://img.shields.io/packagist/l/aesircloud/laravel-actions.svg?style=flat-square"/></a>
</p>

## Features

- **Single-Class Workflow**: Write your logic once in a single `Action` class. No need to duplicate code in controllers, jobs, or other classes.
- **Run Synchronously or as a Queued Job**
    - Call `MyAction::run($data)` for immediate, synchronous execution.
    - Call `MyAction::dispatch($data)` to run the action as a queued job.
- **Controller Integration**
    - Use your `Action` as an invokable controller with __invoke().
    - Or define multiple methods (e.g., index, store) and treat it like a standard Laravel controller.
    - Override `asController()` to parse/validate request data before calling `handle()`. 

## Installation

You can install the package via composer:

```bash
  composer require aesircloud/laravel-actions
```

Laravel’s package auto-discovery will register the service provider automatically. If you need to manually register it, add the following to your `config/app.php` providers array:

```php
AesirCloud\LaravelActions\Providers\ActionServiceProvider::class,
```

## PUBLISHING STUBS

To customize the stub files used for scaffolding, publish the package stubs:

```php
php artisan vendor:publish --tag=actions-stubs
``` 

## Usage

To scaffold a new action, run the following command:

```php
php artisan make:action {ActionName}
```

This will create a new action class in the `app/Actions` directory.

### Basic Example

```php
php artisan make:action CreateUser
```

Creates an action file under `app/Actions/CreateUser.php`.

```php
namespace App\Actions;

use AesirCloud\LaravelActions\Action;

class CreateUser extends Action
{
    public function handle()
    {
        // Your logic here...
    }
}
```

## Running Synchronously

You can run the action synchronously by calling the `run` method on the action class:

```php
$user = CreateUser::run($data);
```
   

## Running as a Job

You can run the action as a job by calling the `dispatch` method on the action class:

```php
$pending = CreateUser::dispatch($data);
```

## Running as an Invokable Controller

```php
// app/Actions/MyAction.php
namespace App\Actions;

use AesirCloud\LaravelActions\Action;
use Illuminate\Http\Request;

class MyAction extends Action
{
    public function handle(): mixed
    {
        return 'Hello world!';
    }

    public function asController(Request $request): mixed
    {
        // e.g., $data = $request->validate([...]);
        return $this->handle();
    }
}
```

```php
// routes/web.php
use App\Actions\MyAction;
use Illuminate\Support\Facades\Route;

Route::get('/my-action', MyAction::class);

```

## Running as a Multi-Method Controller

```php
namespace App\Actions;

use AesirCloud\LaravelActions\Action;
use Illuminate\Http\Request;

class MyAction extends Action
{
    public function handle(): mixed
    {
        return 'Default logic (if you still want to call it externally).';
    }

    // Typical controller method:
    public function index(Request $request): mixed
    {
        return 'Called via index method!';
    }

    public function store(Request $request): mixed
    {
        return 'Called via store!';
    }
}
```

```php
// routes/web.php
Route::get('/my-action', [MyAction::class, 'index']);
Route::post('/my-action', [MyAction::class, 'store']);
```

## Security

If you've found a bug regarding security please mail [security@aesircloud.com](mailto:security@aesircloud.com) instead of using the issue tracker.

## LICENSE

The MIT License (MIT). Please see [License](LICENSE.md) file for more information.