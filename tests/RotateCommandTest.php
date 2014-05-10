<?php

use Intervention\Image\Gd\Commands\RotateCommand as RotateGd;
use Intervention\Image\Imagick\Commands\RotateCommand as RotateImagick;

class RotateCommandTest extends PHPUnit_Framework_TestCase
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
        $image->shouldReceive('setCore')->once()->andReturn($resource);
        $command = new RotateGd(array(45, '#b53717'));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $pixel = Mockery::mock('ImagickPixel', array('#b53717'));
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('rotateimage')->andReturn(true);
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($imagick);
        $command = new RotateImagick(array(45, '#b53717'));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
