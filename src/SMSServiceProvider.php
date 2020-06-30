<?php
namespace RobustTools\SMS;

use Illuminate\Support\ServiceProvider;
use RobustTools\SMS\Console\GenerateSMSServiceProviderEnvVariablesCommand;

class SMSServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/resala.php' => config_path('resala.php'),
            ], 'config');
            $this->commands([
                GenerateSMSServiceProviderEnvVariablesCommand::class
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/resala.php', 'resala');

        // Register the main class to use with the facade
        $this->app->singleton('sms', function () {
            return new SMSManager;
        });
    }
}
