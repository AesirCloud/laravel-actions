<?php

namespace AesirCloud\LaravelActions\Providers;

use Illuminate\Support\ServiceProvider;
use AesirCloud\LaravelActions\Commands\MakeActionCommand;

class ActionsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        // Register the make:action command
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeActionCommand::class,
            ]);

            // Publish stubs (optional)
            $this->publishes([
                __DIR__ . '/../stubs/action.stub' => base_path('stubs/action.stub'),
            ], 'laravel-actions-stubs');
        }
    }

    /**
     * Register bindings in the container.
     */
    public function register()
    {
        //
    }
}
