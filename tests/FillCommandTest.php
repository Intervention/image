<?php

use Intervention\Image\Gd\Commands\FillCommand as FillGd;
use Intervention\Image\Imagick\Commands\FillCommand as FillImagick;
/*
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

    public function testGdFillArray()
    {
        $resource = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($resource);
        $image->shouldReceive('getWidth')->once()->andReturn(800);
        $image->shouldReceive('getHeight')->once()->andReturn(600);
        $command = new FillGd(array(array(50, 50, 50)));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testGdFillArrayWithAlpha()
    {
        $resource = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($resource);
        $image->shouldReceive('getWidth')->once()->andReturn(800);
        $image->shouldReceive('getHeight')->once()->andReturn(600);
        $command = new FillGd(array(array(50, 50, 50, .50)));
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

    public function testImagickColorFill()
    {
        $image = $this->getTestImage('imagick');
        $image->shouldReceive('width')->andReturn(800);
        $image->shouldReceive('height')->andReturn(600);
        $image->getCore()->shouldReceive('drawimage')->times(3);

        $command = new FillImagick(array('666666'));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagickTextureFill()
    {
        $image = $this->getTestImage('imagick');
        $image->shouldReceive('width')->andReturn(800);
        $image->shouldReceive('height')->andReturn(600);
        $image->getCore()->shouldReceive('textureimage')->times(3)->andReturn($image->getCore());
        $image->getCore()->shouldReceive('setimage')->with($image->getCore())->times(3);

        $command = new FillImagick(array('tests/images/circle.png'));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagickColorFillWithCoordinates()
    {
        $image = $this->getTestImage('imagick');
        $image->shouldReceive('width')->andReturn(800);
        $image->shouldReceive('height')->andReturn(600);
        $image->getCore()->shouldReceive('getimage')->andReturn($image->getCore());
        $pixelcolor = Mockery::mock('ImagickPixel');
        $image->getCore()->shouldReceive('getimagepixelcolor')->with(1, 1)->andReturn($pixelcolor);
        $image->getCore()->shouldReceive('getimagealphachannel')->once();
        $image->getCore()->shouldReceive('transparentpaintimage')->once();
        $image->getCore()->shouldReceive('textureimage')->once();

        $command = new FillImagick(array('666666', 1, 1));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
*/