<?php

namespace AesirCloud\LaravelActions\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeActionCommand extends Command
{
    protected $signature = 'make:action
                            {name : The name of the action class (e.g. "Admin/ProcessOrder")}
                            {--force : Overwrite if it already exists}';

    protected $description = 'Create a new Action class';

    protected string $defaultNamespace = 'App\\Actions';
    protected Filesystem $files;

    public function __construct()
    {
        parent::__construct();
        $this->files = new Filesystem;
    }

    public function handle(): int
    {
        $nameInput = str_replace('/', '\\', $this->argument('name'));
        $fqcn = rtrim($this->defaultNamespace, '\\').'\\'.$nameInput;
        $path = $this->classToPath($fqcn);

        if ($this->files->exists($path) && ! $this->option('force')) {
            $this->error("Action already exists at [{$path}]. Use --force to overwrite.");
            return 1;
        }

        if ($this->files->exists($path) && $this->option('force')) {
            $this->files->delete($path);
        }

        $this->files->ensureDirectoryExists(dirname($path));
        $this->writeClassFile($fqcn, $path);

        $this->info("Action created successfully at [{$path}].");
        return 0;
    }

    protected function classToPath(string $fqcn): string
    {
        $relative = Str::replaceFirst('App\\', '', $fqcn);
        $relative = str_replace('\\', '/', $relative);
        return app_path($relative . '.php');
    }

    protected function writeClassFile(string $fqcn, string $path): void
    {
        $namespace = Str::beforeLast($fqcn, '\\');
        $className = Str::afterLast($fqcn, '\\');

        $stubPath = $this->getStub();
        $stub = $this->files->get($stubPath);

        $stub = str_replace('DummyNamespace', $namespace, $stub);
        $stub = str_replace('DummyClass', $className, $stub);

        $this->files->put($path, $stub);
    }

    protected function getStub(): string
    {
        $published = base_path('stubs/action.stub');
        if ($this->files->exists($published)) {
            return $published;
        }
        return __DIR__ . '/../../stubs/action.stub';
    }
}
