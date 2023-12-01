<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeAction extends Command
{
    protected $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    protected $signature = 'make:action {name}';

    protected $description = 'Create a new action class';

    public function handle()
    {
        $className = $this->argument('name');
        $filePath = app_path("Actions/{$className}.php");
        $this->makeDirectory($filePath);
        if ($this->files->exists($filePath)) {
            $this->newLine();
            $this->output->write('<fg=white;bg=red> ERROR </> <fg=white>Action already exists.</>');
            return false;
        }
        $this->files->put($filePath, $this->buildClass($className));
        $this->newLine();
        $this->output->write("<fg=white;bg=blue> INFO </> <fg=white>Action</> [<fg=white;options=bold>app/Actions/{$className}.php</>] <fg=white>created successfully.</>");
    }

    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0755, true, true);
        }
    }

    protected function buildClass($name)
    {
        $stub = $this->files->get(__DIR__ . '/stubs/action.stub');
        return str_replace('DummyClass', $name, $stub);
    }
}