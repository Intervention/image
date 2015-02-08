<?php

use Intervention\Image\Gd\Commands\FillCommand as FillGd;
use Intervention\Image\Imagick\Commands\FillCommand as FillImagick;

class FillCommandTest extends CommandTestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGdFill()
    {
        $driver = Mockery::mock('\Intervention\Image\Gd\Driver');
        $image = $this->getTestImage('gd');
        $image->shouldReceive('getDriver')->andReturn($driver);
        $driver->shouldReceive('init')->with('666666')->once()->andReturn($image);

        $command = new FillGd(array('666666'));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testGdFillWithCoordinates()
    {
        $driver = Mockery::mock('\Intervention\Image\Gd\Driver');
        $image = $this->getTestImage('gd');
        $image->shouldReceive('getDriver')->andReturn($driver);
        $driver->shouldReceive('init')->with('666666')->once()->andReturn($image);
        $driver->shouldReceive('newImage')->with(800, 600)->once()->andReturn($image);

        $command = new FillGd(array('666666', 1, 1));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagickFill()
    {
        $image = $this->getTestImage('imagick');
        $image->shouldReceive('width')->andReturn(800);
        $image->shouldReceive('height')->andReturn(600);
        $image->getCore()->shouldReceive('drawimage');

        $command = new FillImagick(array('666666'));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagickFillWithCoordinates()
    {
        $image = $this->getTestImage('imagick');
        $image->shouldReceive('width')->andReturn(800);
        $image->shouldReceive('height')->andReturn(600);
        $image->getCore()->shouldReceive('getimage')->andReturn($image->getCore());
        $pixelcolor = Mockery::mock('ImagickPixel');
        $image->getCore()->shouldReceive('getimagepixelcolor')->with(1, 1)->andReturn($pixelcolor);
        $image->getCore()->shouldReceive('transparentpaintimage')->with($pixelcolor, 0, 0, false);
        $image->getCore()->shouldReceive('drawimage')->times(3);
        $image->getCore()->shouldReceive('compositeimage')->times(3);
        $image->getCore()->shouldReceive('setimage')->times(3);

        $command = new FillImagick(array('666666', 1, 1));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
