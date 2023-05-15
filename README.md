## Experimental library

1. Publish migration
```shell
php artisan migrate
```

2. Add necessary records to middlewareAliases in Kernel.
```php
'api.log.request' => LogRequest::class,
'api.log.response' => LogResponse::class,
```

3. Usage something like this:
```php
Route::middleware(['api.log.request'])->group(function () {
    Route::get('path/to/your/action', [SomeControllerController::class, 'action'])->middleware('api.log.response');
});
```
