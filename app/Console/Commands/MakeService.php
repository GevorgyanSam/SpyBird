<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeService extends Command
{
    protected $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    protected $signature = 'make:service {name}';

    protected $description = 'Create a new service class';

    public function handle()
    {
        $className = $this->argument('name');
        $filePath = app_path("Services/{$className}.php");
        $this->makeDirectory($filePath);
        if ($this->files->exists($filePath)) {
            $this->newLine();
            $this->output->write('<fg=white;bg=red> ERROR </> <fg=white>Service already exists.</>');
            $this->newLine();
            return false;
        }
        $this->files->put($filePath, $this->buildClass($className));
        $this->newLine();
        $this->output->write("<fg=white;bg=blue> INFO </> <fg=white>Service</> [<fg=white;options=bold>app/Services/{$className}.php</>] <fg=white>created successfully.</>");
        $this->newLine();
    }

    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0755, true, true);
        }
    }

    protected function buildClass($name)
    {
        $stub = $this->files->get(__DIR__ . '/stubs/service.stub');
        return str_replace('DummyClass', $name, $stub);
    }
}