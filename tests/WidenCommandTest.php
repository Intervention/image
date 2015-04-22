<?php

use Intervention\Image\Gd\Commands\WidenCommand as WidenGd;
use Intervention\Image\Imagick\Commands\WidenCommand as WidenImagick;

class WidenCommandTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGd()
    {
        $callback = function ($constraint) { $constraint->aspectRatio(); };
        $resource = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $image = Mockery::mock('Intervention\Image\Image');
        $size = Mockery::mock('Intervention\Image\Size', array(800, 600));
        $size->shouldReceive('resize')->once()->andReturn($size);
        $size->shouldReceive('getWidth')->once()->andReturn(800);
        $size->shouldReceive('getHeight')->once()->andReturn(600);
        $image->shouldReceive('getWidth')->once()->andReturn(800);
        $image->shouldReceive('getHeight')->once()->andReturn(600);
        $image->shouldReceive('getSize')->once()->andReturn($size);
        $image->shouldReceive('getCore')->once()->andReturn($resource);
        $image->shouldReceive('setCore')->once();
        $command = new WidenGd(array(200));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $callback = function ($constraint) { $constraint->upsize(); };
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('scaleimage')->with(300, 200)->once()->andReturn(true);
        $size = Mockery::mock('Intervention\Image\Size', array(800, 600));
        $size->shouldReceive('resize')->once()->andReturn($size);
        $size->shouldReceive('getWidth')->once()->andReturn(300);
        $size->shouldReceive('getHeight')->once()->andReturn(200);
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($imagick);
        $image->shouldReceive('getSize')->once()->andReturn($size);
        $command = new WidenImagick(array(200));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
