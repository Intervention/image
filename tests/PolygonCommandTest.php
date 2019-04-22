<?php

use Intervention\Image\Commands\PolygonCommand;
use PHPUnit\Framework\TestCase;

class PolygonCommandTest extends TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testGd()
    {
        $points = [1, 2, 3, 4, 5, 6];
        $resource = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $driver = Mockery::mock('\Intervention\Image\Gd\Driver');
        $driver->shouldReceive('getDriverName')->once()->andReturn('Gd');
        $image = Mockery::mock('\Intervention\Image\Image');
        $image->shouldReceive('getDriver')->once()->andReturn($driver);
        $image->shouldReceive('getCore')->once()->andReturn($resource);
        $command = new PolygonCommand([$points]);
        $result = $command->execute($image);
        $this->assertTrue($result);
        $this->assertFalse($command->hasOutput());
    }

    public function testImagick()
    {
        $points = [1, 2, 3, 4, 5, 6];
        $imagick = Mockery::mock('\Imagick');
        $imagick->shouldReceive('drawimage');
        $driver = Mockery::mock('\Intervention\Image\Imagick\Driver');
        $driver->shouldReceive('getDriverName')->once()->andReturn('Imagick');
        $image = Mockery::mock('\Intervention\Image\Image');
        $image->shouldReceive('getDriver')->once()->andReturn($driver);
        $image->shouldReceive('getCore')->once()->andReturn($imagick);

        $command = new PolygonCommand([$points]);
        $result = $command->execute($image);
        $this->assertTrue($result);
        $this->assertFalse($command->hasOutput());
    }

}
