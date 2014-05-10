<?php

use Intervention\Image\Gd\Commands\PixelCommand as PixelGd;
use Intervention\Image\Imagick\Commands\PixelCommand as PixelImagick;

class PixelCommandTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGd()
    {
        $resource = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($resource);
        $command = new PixelGd(array('#b53717', 10, 20));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('drawimage')->once()->andReturn(true);
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($imagick);
        $command = new PixelImagick(array('#b53717', 10, 20));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
