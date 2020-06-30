<?php
namespace RobustTools\SMS\Tests;

use Orchestra\Testbench\TestCase;
use RobustTools\SMS\SMSServiceProvider;

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
