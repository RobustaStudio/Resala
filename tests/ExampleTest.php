<?php

namespace RobustTools\Resala\Tests;

use Orchestra\Testbench\TestCase;
use RobustTools\Resala\SMSServiceProvider;

class ExampleTest extends TestCase
{
    public function test_true_is_true()
    {
        $this->assertTrue(true);
    }

    protected function getPackageProviders($app)
    {
        return [SMSServiceProvider::class];
    }
}
