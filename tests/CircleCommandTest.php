<?php

use Intervention\Image\Commands\CircleCommand;
use PHPUnit\Framework\TestCase;

class CircleCommandTest extends TestCase
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
        $command = new CircleCommand([250, 10, 20]);
        $result = $command->execute($image);
        $this->assertTrue($result);
        $this->assertFalse($command->hasOutput());
    }

    public function testImagick()
    {
        $imagick = Mockery::mock('\Imagick');
        $imagick->shouldReceive('drawimage');
        $driver = Mockery::mock('\Intervention\Image\Imagick\Driver');
        $driver->shouldReceive('getDriverName')->once()->andReturn('Imagick');
        $image = Mockery::mock('\Intervention\Image\Image');
        $image->shouldReceive('getDriver')->once()->andReturn($driver);
        $image->shouldReceive('getCore')->once()->andReturn($imagick);

        $command = new CircleCommand([25, 10, 20]);
        $result = $command->execute($image);
        $this->assertTrue($result);
        $this->assertFalse($command->hasOutput());
    }

}
