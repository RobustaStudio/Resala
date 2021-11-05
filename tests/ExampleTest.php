<?php
namespace RobustTools\Resala\Tests;

use Orchestra\Testbench\TestCase;
use RobustTools\Resala\SMSServiceProvider;

class ExampleTest extends TestCase
{
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }

    protected function getPackageProviders($app)
    {
        return [SMSServiceProvider::class];
    }
}
