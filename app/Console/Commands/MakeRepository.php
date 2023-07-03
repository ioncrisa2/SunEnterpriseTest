<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name} {--class=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make Repository Class with interface';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $name = $this->argument('name');
        $modelClass = $this->option('class');
        $repositoryPath = app_path("Repositories/{$name}Repository.php");

        if (File::exists($repositoryPath)) {
            $this->error("Repository {$name} already Exists");
            return;
        }

        $this->createModel($modelClass);
        File::put($repositoryPath, $this->repositoryStub($name, $modelClass));
        $this->createInterface($name);
        $this->updateRepository($name);

        $this->info("Repository {$name} created successfully!!");
    }
    
    private function createModel($name): void
    {
        $modelPath = app_path("Models/{$name}.php");

        if (!File::exists($modelPath)) {
            $stub = file_get_contents(app_path("Console/Commands/stubs/model.stub"));
            $stub = str_replace("{{modelClass}}", $name, $stub);

            File::put($modelPath, $stub);
        }    
    }

    private function createInterface($name): void
    {
        $interfacePath = app_path("Interfaces/RepositoriesInterface/{$name}RepositoryInterface.php");
        if (File::exists($interfacePath)) {
            return;
        }

        File::put($interfacePath, $this->interfaceStub($name));
    }

    private function repositoryStub($name, $className): string
    {
        $stub = file_get_contents(app_path("Console/Commands/stubs/repository.stub"));
        $stub = str_replace("{{repositoryName}}", $name, $stub);
        $stub = str_replace("{{modelClass}}", $className, $stub);
        return $stub;
    }

    private function interfaceStub($name): array|string
    {
        $stub = file_get_contents(app_path("Console/Commands/stubs/interface.stub"));
        return str_replace("{{repositoryName}}", $name, $stub);
    }

    private function updateRepository($name)
    {
        $repositoryPath = app_path("Repositories/{$name}Repository.php");

        if (File::exists($repositoryPath)) {
            $repositoryContent = file_get_contents($repositoryPath);

            // Add the use statement for the interface class
            $interfaceNamespace = "App\Interfaces\RepositoriesInterface\\" . $name . "RepositoryInterface";
            $useStatement = "use {$interfaceNamespace};" . PHP_EOL . PHP_EOL;
            $repositoryContent = preg_replace('/^use App\\\\Models\\\\.*;/m', "$0\n{$useStatement}", $repositoryContent);

            // Implement the interface
            $repositoryContent = str_replace(
                'class ' . $name . 'Repository',
                'class ' . $name . 'Repository implements ' . $name . 'RepositoryInterface',
                $repositoryContent
            );

            File::put($repositoryPath, $repositoryContent);
        }
    }

}
