<?php

use Illuminate\Contracts\Config\Repository as Config;
use Mockery as m;

class MultilogTest extends TestCase
{
    private $multilog;

    public function setUp()
    {
        parent::setUp();
        $stubConfig = [

        ];
        $configMock = m::mock(Config::class);
        $configMock->shouldReceive('get')->with('multilog')->andReturn($stubConfig);
        $this->multilog = new Multilog($configMock);
    }
}
