<?php

use Intervention\Image\Commands\TextCommand;
use PHPUnit\Framework\TestCase;

class TextCommandTest extends TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testGd()
    {
        $resource = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $driver = Mockery::mock('\Intervention\Image\Gd\Driver');
        $driver->shouldReceive('getDriverName')->once()->andReturn('Gd');
        $image = Mockery::mock('\Intervention\Image\Image');
        $image->shouldReceive('getDriver')->once()->andReturn($driver);
        $image->shouldReceive('getCore')->once()->andReturn($resource);
        $command = new TextCommand(['test', 10, 20]);
        $result = $command->execute($image);
        $this->assertTrue($result);
        $this->assertFalse($command->hasOutput());
    }

    public function testImagick()
    {
        # code...
    }

}
