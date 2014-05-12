<?php

use Intervention\Image\Gd\Commands\FitCommand as FitGd;
use Intervention\Image\Imagick\Commands\FitCommand as FitImagick;

class FitCommandTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGd()
    {
        $fitted_size = Mockery::mock('\Intervention\Image\Size', array(800, 400));
        $fitted_size->shouldReceive('getWidth')->once()->andReturn(800);
        $fitted_size->shouldReceive('getHeight')->once()->andReturn(400);
        $fitted_size->pivot = Mockery::mock('\Intervention\Image\Point', array(0, 100));
        $original_size = Mockery::mock('\Intervention\Image\Size', array(800, 600));
        $original_size->shouldReceive('fit')->once()->andReturn($fitted_size);
        $resource = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getSize')->once()->andReturn($original_size);
        $image->shouldReceive('getCore')->once()->andReturn($resource);
        $image->shouldReceive('setCore')->once();
        $command = new FitGd(array(200, 100));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $fitted_size = Mockery::mock('\Intervention\Image\Size', array(800, 400));
        $fitted_size->pivot = Mockery::mock('\Intervention\Image\Point', array(0, 100));
        $original_size = Mockery::mock('\Intervention\Image\Size', array(800, 600));
        $original_size->shouldReceive('fit')->once()->andReturn($fitted_size);
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('cropimage')->with(800, 400, 0, 100)->andReturn(true);
        $imagick->shouldReceive('resizeimage')->with(200, 100, \Imagick::FILTER_BOX, 1)->andReturn(true);
        $imagick->shouldReceive('setimagepage')->with(0, 0, 0, 0)->andReturn(true);
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getSize')->once()->andReturn($original_size);
        $image->shouldReceive('getCore')->times(3)->andReturn($imagick);
        $command = new FitImagick(array(200, 100));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
