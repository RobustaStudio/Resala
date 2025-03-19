<?php
namespace RobustTools\Resala\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishProviderEnvVariablesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resala:make {driver}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate vodafone required environment variables';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $driver = $this->argument('driver');

        if (! array_key_exists($driver, config('resala.map'))) {
            $this->error("provided driver does not exists, you may check available drivers: " . implode(", ", array_keys(config('resala.map'))));

            return;
        }

        if (File::exists($this->getEnvPath())) {
            $content = $this->getStubContent();
            File::append($this->getEnvPath(), $content);
            $this->info("environment variables set successfully...");

            return;
        }

        $this->error(".env file does not exist");
    }

    /**
     * Get laravel env file path.
     *
     * @return string
     */
    private function getEnvPath()
    {
        return $this->laravel->basePath() . DIRECTORY_SEPARATOR . '.env';
    }

    private function getStubContent(): string
    {
        if ($this->argument('driver') == 'vodafone') {
            return File::get(__DIR__ . "/../../stubs/vodafone.env.stub");
        }

        if ($this->argument('driver') == "connekio") {
            return File::get(__DIR__ . "/../../stubs/connekio.env.stub");
        }

        if ($this->argument('driver') == "infobip") {
            return File::get(__DIR__ . "/../../stubs/infobip.env.stub");
        }

        if ($this->argument('driver') == "vectory_link") {
            return File::get(__DIR__ . "/../../stubs/vectory_link.env.stub");
        }

        if ($this->argument('driver') == "brandencode") {
            return File::get(__DIR__ . "/../../stubs/brandencode.env.stub");
        }
    }
}
