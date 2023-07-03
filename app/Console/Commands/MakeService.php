<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Service';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $name = $this->argument('name');
        $servicePath = app_path("Services/{$name}.php");
        if(File::exists($servicePath)){
            $this->error("Service {$name} already exists!!");
            return;
        }
        
        File::put($servicePath,$this->serviceStub($name));
        $this->info("Service {$name} created successfully!!");
    }
    
    private function serviceStub($name): string
    {
        $stub =  file_get_contents(app_path("Console/Commands/stubs/service.stub"));
        return str_replace("{{serviceName}}", $name,$stub);
    }
}
