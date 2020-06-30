<?php
namespace RobustTools\SMS\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateSMSServiceProviderEnvVariablesCommand extends Command
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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $driver = $this->argument('driver');

        if (! array_key_exists($driver, config('resala.map'))) {
            $this->error("provided driver does not exists, you may check available drivers: " . implode(", ", array_keys(config('resala.map'))));

            return;
        }

        if (File::exists($this->getEnvPath()) && ! $this->variablesAlreadySet()) {
            $content = $this->getStubContent();
            File::append($this->getEnvPath(), $content);
            $this->info("environment variables set successfully...");

            return;
        }
        $this->warn("check if the .env file exists or vodafone variables might already exists");
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

    /**
     * @return string
     */
    private function getEnvFileContents()
    {
        return File::get($this->getEnvPath());
    }

    /**
     * Check if vodafone env variables already set.
     *
     * @return bool
     */
    private function variablesAlreadySet(): bool
    {
        $variables = explode('=', $this->getStubContent());
        foreach ($variables as $variable) {
            return Str::contains($this->getEnvFileContents(), $variable);
        }

        return false;
    }

    /**
     * @return string
     */
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
    }
}
