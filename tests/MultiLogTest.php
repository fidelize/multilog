<?php

use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Container\Container as App;
use Karlomikus\Multilog\Multilog;
use Mockery as m;

class MultilogTest extends PHPUnit_Framework_TestCase
{
    private $config;

    protected function setUp()
    {
        $this->config = [
            'test1' => [
                'stream' => 'test1.log',
                'daily'  => false,
                'format' => [
                    'date'   => 'Y-m-d H:i:s',
                    'output' => "[%datetime%] %message% %context% %extra%\n",
                ],
            ],
            'test2' => [
                'stream' => 'test2.log',
                'daily'  => false
            ],
            'test3' => [
                'stream' => 'test3.log',
                'daily'  => true
            ]
        ];
    }

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

    protected function tearDown()
    {
        m::close();
    }
}
