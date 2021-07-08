<?php

use Intervention\Image\Gd\Commands\ColorizeToRGBACommand as ColorizeGd;
use Intervention\Image\Imagick\Commands\ColorizeToRGBACommand as ColorizeImagick;

class ColorizeToRGBACommandTest extends PHPUnit_Framework_TestCase
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
        $command = new ColorizeGd([20, 0, 40, 100]);
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('colorizeImage')->once()->andReturn(true);

        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->times(1)->andReturn($imagick);

        $command = new ColorizeImagick([20, 0, 40, 100]);
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
