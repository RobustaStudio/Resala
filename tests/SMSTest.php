<?php
namespace RobustTools\Resala\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use RobustTools\Resala\Contracts\SMSDriverResponseInterface;
use RobustTools\Resala\Facades\SMS as SMSFacade;
use RobustTools\Resala\SMS;
use RobustTools\Resala\SMSServiceProvider;

class SMSTest extends OrchestraTestCase
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
    public function testSMS()
    {
        $this->smsMock = \Mockery::mock(SMS::class)->shouldReceive('via','to','message','send')
            ->andReturn(\Mockery::mock(SMSDriverResponseInterface::class));;
        (new SMS)->via('vodafone')->to('01000000000')->message("test")->send();

    }
}
