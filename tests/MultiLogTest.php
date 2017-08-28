<?php

use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Container\Container as App;
use Karlomikus\Multilog\Multilog;
use Mockery as m;
use Monolog\Logger;

class MultilogTest extends PHPUnit_Framework_TestCase
{
    private $config;

    /** @test */
    public function it_creates_a_channel_from_given_callback()
    {
        $stubConfig = [
            'test' => function ($channel) {
                return new Logger($channel);
            }
        ];

        $configMock = m::mock(Config::class);
        $appMock = m::mock(App::class);

        $configMock->shouldReceive('get')->with('multilog.channels')->andReturn($stubConfig);
        $appMock->shouldReceive('make')->with('path.storage')->andReturn('/test/storage');
        $multilog = new Multilog($configMock, $appMock);

        $logger = $multilog->channel('test');
        $this->assertInstanceOf(Monolog\Logger::class, $logger);
        $this->assertEquals('test', $logger->getName());
    }

    /** @test */
    public function it_creates_a_channel_with_a_wildcard_configuration()
    {
        $stubConfig = [
            'industries.*' => function ($channel) {
                return new Logger($channel);
            }
        ];

        $configMock = m::mock(Config::class);
        $appMock = m::mock(App::class);

        $configMock->shouldReceive('get')->with('multilog.channels')->andReturn($stubConfig);
        $appMock->shouldReceive('make')->with('path.storage')->andReturn('/test/storage');
        $multilog = new Multilog($configMock, $appMock);

        $logger = $multilog->channel('industries.acme');
        $this->assertInstanceOf(Monolog\Logger::class, $logger);
        $this->assertEquals('industries.acme', $logger->getName());
    }

    /** @test */
    public function it_call_channel_via_alias()
    {
        $stubConfig = [
            'test' => function ($channel) {
                return new Logger($channel);
            }
        ];

        $configMock = m::mock(Config::class);
        $appMock = m::mock(App::class);

        $configMock->shouldReceive('get')->with('multilog.channels')->andReturn($stubConfig);
        $appMock->shouldReceive('make')->with('path.storage')->andReturn('/test/storage');
        $multilog = new Multilog($configMock, $appMock);

        $logger = $multilog->c('test');
        $this->assertInstanceOf(Monolog\Logger::class, $logger);
        $this->assertEquals('test', $logger->getName());
    }

    /** @test */
    public function it_returns_null_when_channel_not_found()
    {
        $stubConfig = [];

        $configMock = m::mock(Config::class);
        $appMock = m::mock(App::class);

        $configMock->shouldReceive('get')->with('multilog.channels')->andReturn($stubConfig);
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
