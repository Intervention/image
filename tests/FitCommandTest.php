<?php

use Intervention\Image\Gd\Commands\FitCommand as FitGd;
use Intervention\Image\Imagick\Commands\FitCommand as FitImagick;

class FitCommandTest extends CommandTestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGdFit()
    {
        $image = $this->getTestImage('gd');

        $cropped_size = Mockery::mock('\Intervention\Image\Size', array(800, 400));
        $cropped_size->shouldReceive('getWidth')->times(2)->andReturn(800);
        $cropped_size->shouldReceive('getHeight')->times(2)->andReturn(400);
        $cropped_size->shouldReceive('resize')->with(200, 100, null)->once()->andReturn($cropped_size);
        $cropped_size->pivot = Mockery::mock('\Intervention\Image\Point', array(0, 100));
        $original_size = Mockery::mock('\Intervention\Image\Size', array(800, 600));
        $original_size->shouldReceive('fit')->with(Mockery::any(), 'center')->once()->andReturn($cropped_size);
        $image->shouldReceive('getSize')->once()->andReturn($original_size);
        
        $command = new FitGd(array(200, 100));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testGdFitWithPosition()
    {
        $image = $this->getTestImage('gd');

        $cropped_size = Mockery::mock('\Intervention\Image\Size', array(800, 400));
        $cropped_size->shouldReceive('getWidth')->times(2)->andReturn(800);
        $cropped_size->shouldReceive('getHeight')->times(2)->andReturn(400);
        $cropped_size->shouldReceive('resize')->with(200, 100, null)->once()->andReturn($cropped_size);
        $cropped_size->pivot = Mockery::mock('\Intervention\Image\Point', array(0, 100));
        $original_size = Mockery::mock('\Intervention\Image\Size', array(800, 600));
        $original_size->shouldReceive('fit')->with(Mockery::any(), 'top-left')->once()->andReturn($cropped_size);
        $image->shouldReceive('getSize')->once()->andReturn($original_size);
        
        $command = new FitGd(array(200, 100, null, 'top-left'));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagickFit()
    {
        $image = $this->getTestImage('imagick');

        $cropped_size = Mockery::mock('\Intervention\Image\Size', array(800, 400));
        $cropped_size->shouldReceive('getWidth')->times(3)->andReturn(200);
        $cropped_size->shouldReceive('getHeight')->times(3)->andReturn(100);
        $cropped_size->shouldReceive('resize')->with(200, 100, null)->once()->andReturn($cropped_size);
        $cropped_size->pivot = Mockery::mock('\Intervention\Image\Point', array(0, 100));
        $original_size = Mockery::mock('\Intervention\Image\Size', array(800, 600));
        $original_size->shouldReceive('fit')->with(Mockery::any(), 'center')->once()->andReturn($cropped_size);
        $image->getCore()->shouldReceive('cropimage')->with(800, 400, 0, 100)->andReturn(true);
        $image->getCore()->shouldReceive('resizeimage')->with(200, 100, \Imagick::FILTER_BOX, 1)->andReturn(true);
        $image->getCore()->shouldReceive('setimagepage')->with(0, 0, 0, 0)->andReturn(true);
        $image->shouldReceive('getSize')->once()->andReturn($original_size);
        
        $command = new FitImagick(array(200, 100));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagickFitWithPosition()
    {
        $image = $this->getTestImage('imagick');

        $cropped_size = Mockery::mock('\Intervention\Image\Size', array(800, 400));
        $cropped_size->shouldReceive('getWidth')->times(3)->andReturn(200);
        $cropped_size->shouldReceive('getHeight')->times(3)->andReturn(100);
        $cropped_size->shouldReceive('resize')->with(200, 100, null)->once()->andReturn($cropped_size);
        $cropped_size->pivot = Mockery::mock('\Intervention\Image\Point', array(0, 100));
        $original_size = Mockery::mock('\Intervention\Image\Size', array(800, 600));
        $original_size->shouldReceive('fit')->with(Mockery::any(), 'top-left')->once()->andReturn($cropped_size);
        $image->getCore()->shouldReceive('cropimage')->with(800, 400, 0, 100)->andReturn(true);
        $image->getCore()->shouldReceive('resizeimage')->with(200, 100, \Imagick::FILTER_BOX, 1)->andReturn(true);
        $image->getCore()->shouldReceive('setimagepage')->with(0, 0, 0, 0)->andReturn(true);
        $image->shouldReceive('getSize')->once()->andReturn($original_size);

        
        $command = new FitImagick(array(200, 100, null, 'top-left'));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
