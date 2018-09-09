<?php

use Intervention\Image\Gd\Commands\RotateCommand as RotateGd;
use Intervention\Image\Imagick\Commands\RotateCommand as RotateImagick;
use PHPUnit\Framework\TestCase;

class RotateCommandTest extends TestCase
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
        $command = new RotateGd([45, '#b53717']);
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $pixel = Mockery::mock('ImagickPixel', ['#b53717']);
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('rotateimage')->andReturn(true);
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($imagick);
        $command = new RotateImagick([45, '#b53717']);
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagickWithLargeRotation()
    {
        $rotation = 45;
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('rotateimage')->with(Mockery::type('object'), -$rotation)->andReturn(true);
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($imagick);
        $command = new RotateImagick([$rotation + (360 * 1000), '#b53717']);
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
