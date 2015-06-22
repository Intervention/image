<?php

use Intervention\Image\Gd\Commands\ResizeCanvasCommand as ResizeCanvasGd;
use Intervention\Image\Imagick\Commands\ResizeCanvasCommand as ResizeCanvasImagick;

class ResizeCanvasCommandTest extends CommandTestCase
{
    public function testGd()
    {
        $resource = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $canvas_pos = Mockery::mock('\Intervention\Image\Point', array(0, 0));
        $canvas_size = Mockery::mock('\Intervention\Image\Size', array(820, 640));
        $canvas_size->shouldReceive('align')->with('center')->andReturn($canvas_size);
        $canvas_size->shouldReceive('relativePosition')->andReturn($canvas_pos);
        $image_pos = Mockery::mock('\Intervention\Image\Point', array(0, 0));
        $image_size = Mockery::mock('\Intervention\Image\Size', array(800, 600));
        $image_size->shouldReceive('align')->with('center')->andReturn($image_size);
        $image_size->shouldReceive('relativePosition')->andReturn($image_pos);
        $canvas = Mockery::mock('\Intervention\Image\Image');
        $canvas->shouldReceive('getCore')->times(5)->andReturn($resource);
        $canvas->shouldReceive('getSize')->andReturn($canvas_size);
        $driver = Mockery::mock('\Intervention\Image\Gd\Driver');
        $driver->shouldReceive('newImage')->with(820, 640, '#b53717')->once()->andReturn($canvas);
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getDriver')->once()->andReturn($driver);
        $image->shouldReceive('getSize')->once()->andReturn($image_size);
        $image->shouldReceive('getWidth')->once()->andReturn(800);
        $image->shouldReceive('getHeight')->once()->andReturn(600);
        $image->shouldReceive('getCore')->once()->andReturn($resource);
        $image->shouldReceive('setCore')->once();
        $command = new ResizeCanvasGd(array(20, 40, 'center', true, '#b53717'));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $canvas_pos = Mockery::mock('\Intervention\Image\Point', array(0, 0));
        $canvas_size = Mockery::mock('\Intervention\Image\Size', array(820, 640));
        $canvas_size->shouldReceive('align')->with('center')->andReturn($canvas_size);
        $canvas_size->shouldReceive('relativePosition')->andReturn($canvas_pos);
        $image_pos = Mockery::mock('\Intervention\Image\Point', array(0, 0));
        $image_size = Mockery::mock('\Intervention\Image\Size', array(800, 600));
        $image_size->shouldReceive('align')->with('center')->andReturn($image_size);
        $image_size->shouldReceive('relativePosition')->andReturn($image_pos);
        
        $canvas = $this->getTestImage('imagick');
        $canvas->getCore()->shouldReceive('drawimage')->once();
        $canvas->getCore()->shouldReceive('transparentpaintimage')->once();
        $canvas->getCore()->shouldReceive('setimagecolorspace')->once();
        $canvas->shouldReceive('getSize')->andReturn($canvas_size);
        $canvas->shouldReceive('pickColor')->with(0, 0, 'hex')->once()->andReturn('#000000');
        $driver = Mockery::mock('\Intervention\Image\Imagick\Driver');
        $driver->shouldReceive('newImage')->with(820, 640, '#b53717')->once()->andReturn($canvas);
        
        $image = $this->getTestImage('imagick');
        $image->getCore()->shouldReceive('cropimage')->with(800, 600, 0, 0)->once();
        $image->getCore()->shouldReceive('compositeimage')->with($image->getCore(), 40, 0, 0)->once();
        $image->getCore()->shouldReceive('setimagepage')->with(0, 0, 0, 0)->once();
        
        $image->getCore()->shouldReceive('getimagecolorspace')->once();
        $image->shouldReceive('getDriver')->once()->andReturn($driver);
        $image->shouldReceive('getSize')->once()->andReturn($image_size);
        $image->shouldReceive('getWidth')->once()->andReturn(800);
        $image->shouldReceive('getHeight')->once()->andReturn(600);
        $image->shouldReceive('setCore')->once();
        $command = new ResizeCanvasImagick(array(20, 40, 'center', true, '#b53717'));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

}
