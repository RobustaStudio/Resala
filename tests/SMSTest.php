<?php
namespace RobustTools\Resala\Tests;

use Mockery;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use RobustTools\Resala\Contracts\SMSDriverInterface;
use RobustTools\Resala\Contracts\SMSDriverResponseInterface;
use RobustTools\Resala\Facades\SMS as SMSFacade;
use RobustTools\Resala\SMSServiceProvider;
class SMSTest extends OrchestraTestCase
{
    private $mockXmlResponse;
    private $mockStream;
    private $mockResponse;
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
        $sms = $this->createMockXMLResponse()
                    ->createMockStream()
                    ->createMockResponse()
                    ->mockHTTP()
                    ->sendSMS();

        $this->assertInstanceOf(SMSDriverResponseInterface::class, $sms);

    }

    private function createMockXMLResponse(): self
    {
        $this->mockXmlResponse = <<<XML
                                <?xml version="1.0" encoding="UTF-8"?>
                                    <Response>
                                    </Response>
                                XML;
        return $this;
    }

    private function createMockStream(): self
    {
        $this->mockStream = Mockery::mock(StreamInterface::class);
        $this->mockStream->shouldReceive('__toString')
            ->andReturn($this->mockXmlResponse);
        $this->mockStream->shouldReceive('getContents')
            ->andReturn($this->mockXmlResponse);
        return $this;
    }

    private function createMockResponse(): self
    {
        $this->mockResponse = Mockery::mock(ResponseInterface::class);
        $this->mockResponse
             ->shouldReceive('getBody')
             ->andReturn($this->mockStream);
        $this->mockResponse
             ->shouldReceive('getStatusCode')
             ->andReturn(200);
        $this->mockResponse
             ->shouldReceive('getHeaders')
             ->andReturn([
                 'Content-Type' => ['application/xml; charset=UTF8']
             ]);
        return $this;
    }

    private function mockHTTP(): self
    {
        Mockery::mock('alias:RobustTools\Resala\Support\HTTP')
            ->shouldReceive('post')
            ->andReturn($this->mockResponse);
        return $this;
    }

    private function sendSMS()
    {
        return SMSFacade::via('vodafone')
                        ->to('01000000000')
                        ->message("test")
                        ->send();
    }
}
