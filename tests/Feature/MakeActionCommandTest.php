<?php

use AesirCloud\LaravelActions\Commands\MakeActionCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    $actionsPath = app_path('Actions');

    if (File::exists($actionsPath)) {
        File::deleteDirectory($actionsPath);
    }

    $customStubPath = base_path('stubs/action.stub');
    if (File::exists($customStubPath)) {
        File::delete($customStubPath);
    }
});

// Helper to replicate the command's classToPath logic.
function computeExpectedPath(string $fqcn): string {
    $relative = str_replace('App\\', '', $fqcn);
    $relative = str_replace('\\', '/', $relative);
    return app_path($relative . '.php');
}

test('fails if file exists', function () {
    $name = 'TestAction';
    $fqcn = 'App\\Actions\\' . $name;
    $expectedPath = computeExpectedPath($fqcn);

    File::ensureDirectoryExists(dirname($expectedPath));
    File::put($expectedPath, 'Existing file content');

    $exitCode = Artisan::call('make:action', [
        'name' => $name,
    ]);

    expect($exitCode)->toBe(1)
        ->and(File::get($expectedPath))->toBe('Existing file content');
});

test('overwrites existing with force', function () {
    $name = 'TestAction';
    $fqcn = 'App\\Actions\\' . $name;
    $expectedPath = computeExpectedPath($fqcn);

    // Create an existing file.
    File::ensureDirectoryExists(dirname($expectedPath));
    File::put($expectedPath, 'Old content');

    // Run the command with the --force option.
    $exitCode = Artisan::call('make:action', [
        'name'    => $name,
        '--force' => true,
    ]);

    // Expect success (exit code 0) and the file content to have been overwritten.
    expect($exitCode)->toBe(0)
        ->and(File::get($expectedPath))->not->toBe('Old content');
});

test('can create a sub-namespace action', function () {
    $name = 'Admin/ProcessOrder';
    $fqcn = 'App\\Actions\\Admin\\ProcessOrder';
    $expectedPath = computeExpectedPath($fqcn);

    // Ensure the target file does not exist.
    if (File::exists($expectedPath)) {
        File::delete($expectedPath);
    }

    // Run the command.
    $exitCode = Artisan::call('make:action', [
        'name' => $name,
    ]);

    // Verify that the file is created in the proper subdirectory.
    expect(File::exists($expectedPath))->toBeTrue()
        ->and($exitCode)->toBe(0);
});

test('uses custom published stub if present', function () {
    // Create a custom stub file at the published location.
    $customStubPath = base_path('stubs/action.stub');
    File::ensureDirectoryExists(dirname($customStubPath));
    File::put($customStubPath, 'Custom Stub Content with DummyNamespace and DummyClass');

    $name = 'CustomStubAction';
    $fqcn = 'App\\Actions\\' . $name;
    $expectedPath = computeExpectedPath($fqcn);

    // Ensure the target file does not exist.
    if (File::exists($expectedPath)) {
        File::delete($expectedPath);
    }

    // Run the command.
    $exitCode = Artisan::call('make:action', [
        'name' => $name,
    ]);

    // Check that the file uses the custom stub.
    $content = File::get($expectedPath);

    expect($content)->toContain('Custom Stub Content')
        ->and($content)->not->toContain('DummyNamespace')
        ->and($content)->not->toContain('DummyClass')
        ->and($exitCode)->toBe(0);

    // Clean up the custom stub.
    File::delete($customStubPath);
});
