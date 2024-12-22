<?php
namespace RobustTools\Resala\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use RobustTools\Resala\Contracts\SMSDriverInterface;
use RobustTools\Resala\Factories\SMSDriverFactory;
use RobustTools\Resala\SMSServiceProvider;

class SMSDriverFactoryTest extends OrchestraTestCase
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            SMSServiceProvider::class,
        ];
    }
    public function testCreatMethod()
    {
        \Mockery::mock('alias:' . SMSDriverFactory::class)
                ->shouldReceive('create')
                ->once()
                ->andReturn(\Mockery::mock(SMSDriverInterface::class));;

        SMSDriverFactory::create();
    }
}
