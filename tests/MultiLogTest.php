<?php

use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Container\Container as App;
use Karlomikus\Multilog\Multilog;
use Mockery as m;

class MultilogTest extends PHPUnit_Framework_TestCase
{
    private $config;

    /** @test */
    public function it_creates_a_channel_with_minimum_configuration()
    {
        $stubConfig = [
            'test' => [
                'stream' => 'test.log',
                'daily'  => false
            ]
        ];

        $configMock = m::mock(Config::class);
        $appMock = m::mock(App::class);

        $configMock->shouldReceive('get')->with('multilog')->andReturn($stubConfig);
        $appMock->shouldReceive('make')->with('path.storage')->andReturn('/test/storage');
        $multilog = new Multilog($configMock, $appMock);

        $logger = $multilog->channel('test');
        $this->assertInstanceOf(Monolog\Logger::class, $logger);
        $this->assertInstanceOf(Monolog\Handler\StreamHandler::class, $logger->getHandlers()[0]);
    }

    /** @test */
    public function it_creates_a_channel_with_daily_configuration()
    {
        $stubConfig = [
            'test' => [
                'stream' => 'test.log',
                'daily'  => true
            ]
        ];

        $configMock = m::mock(Config::class);
        $appMock = m::mock(App::class);

        $configMock->shouldReceive('get')->with('multilog')->andReturn($stubConfig);
        $appMock->shouldReceive('make')->with('path.storage')->andReturn('/test/storage');
        $multilog = new Multilog($configMock, $appMock);

        $logger = $multilog->channel('test');
        $this->assertInstanceOf(Monolog\Logger::class, $logger);
        $this->assertInstanceOf(Monolog\Handler\RotatingFileHandler::class, $logger->getHandlers()[0]);
    }

    /** @test */
    public function it_creates_a_channel_with_full_configuration()
    {
        $stubConfig = [
            'test' => [
                'stream' => 'test1.log',
                'daily'  => false,
                'format' => [
                    'date'   => 'Y-m-d H:i:s',
                    'output' => "[%datetime%] %message% %context% %extra%\n",
                ]
            ]
        ];

        $configMock = m::mock(Config::class);
        $appMock = m::mock(App::class);

        $configMock->shouldReceive('get')->with('multilog')->andReturn($stubConfig);
        $appMock->shouldReceive('make')->with('path.storage')->andReturn('/test/storage');
        $multilog = new Multilog($configMock, $appMock);

        $logger = $multilog->channel('test');
        $this->assertInstanceOf(Monolog\Logger::class, $logger);
        $this->assertInstanceOf(Monolog\Formatter\LineFormatter::class, $logger->getHandlers()[0]->getFormatter());
    }

    /** @test */
    public function it_call_channel_via_alias()
    {
        $stubConfig = [
            'test' => [
                'stream' => 'test.log',
                'daily'  => false
            ]
        ];

        $configMock = m::mock(Config::class);
        $appMock = m::mock(App::class);

        $configMock->shouldReceive('get')->with('multilog')->andReturn($stubConfig);
        $appMock->shouldReceive('make')->with('path.storage')->andReturn('/test/storage');
        $multilog = new Multilog($configMock, $appMock);

        $logger = $multilog->c('test');
        $this->assertInstanceOf(Monolog\Logger::class, $logger);
        $this->assertInstanceOf(Monolog\Handler\StreamHandler::class, $logger->getHandlers()[0]);
    }

    /** @test */
    public function it_returns_null_when_channel_not_found()
    {
        $stubConfig = [];

        $configMock = m::mock(Config::class);
        $appMock = m::mock(App::class);

        $configMock->shouldReceive('get')->with('multilog')->andReturn($stubConfig);
        $appMock->shouldReceive('make')->with('path.storage')->andReturn('/test/storage');
        $multilog = new Multilog($configMock, $appMock);

        $logger = $multilog->channel('test');
        $this->assertNull($logger);
    }

    protected function tearDown()
    {
        m::close();
    }
}
