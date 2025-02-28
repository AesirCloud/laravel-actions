<?php

namespace Tests;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as BaseTestCase;
use AesirCloud\LaravelActions\Providers\ActionsServiceProvider;
use Illuminate\Bus\BusServiceProvider;
use ReflectionException;
use Tests\Fixtures\MyAction;

abstract class TestCase extends BaseTestCase
{
    /**
     * Register your package's providers (and any others needed).
     */
    protected function getPackageProviders($app): array
    {
        return [
            ActionsServiceProvider::class,
            BusServiceProvider::class, // For Bus::fake(), etc.
        ];
    }

    /**
     * Environment setup: define a test route for 'test-action'.
     *
     * @param Application $app
     * @throws ReflectionException
     */
    protected function defineEnvironment($app): void
    {
        // If your "MyAction" is a single-action controller:
        $app['router']->post('test-action', MyAction::class);
    }
}
