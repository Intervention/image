<?php

use Intervention\Image\Gd\Commands\PixelateCommand as PixelateGd;
use Intervention\Image\Imagick\Commands\PixelateCommand as PixelateImagick;

class PixelateCommandTest extends PHPUnit_Framework_TestCase
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
        $command = new PixelateGd(array(10));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('scaleimage')->with(80, 60)->once()->andReturn(true);
        $imagick->shouldReceive('scaleimage')->with(800, 600)->once()->andReturn(true);
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->times(2)->andReturn($imagick);
        $image->shouldReceive('getWidth')->once()->andReturn(800);
        $image->shouldReceive('getHeight')->once()->andReturn(600);
        $command = new PixelateImagick(array(10));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
