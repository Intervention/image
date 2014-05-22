<?php

use Intervention\Image\Gd\Commands\SharpenCommand as SharpenGd;
use Intervention\Image\Imagick\Commands\SharpenCommand as SharpenImagick;

class SharpenCommandTest extends PHPUnit_Framework_TestCase
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
        $command = new SharpenGd(array(50));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('unsharpmaskimage')->with(1, 1, 8, 0)->andReturn(true);
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($imagick);
        $command = new SharpenImagick(array(50));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
