<?php

use Intervention\Image\Gd\Commands\FlipCommand as FlipGd;
use Intervention\Image\Imagick\Commands\FlipCommand as FlipImagick;

class FlipCommandTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGd()
    {
        $size = Mockery::mock('\Intervention\Image\Size', array(800, 600));
        $resource = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getSize')->once()->andReturn($size);
        $image->shouldReceive('getCore')->once()->andReturn($resource);
        $image->shouldReceive('setCore')->once();
        $command = new FlipGd(array('h'));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('flopimage')->with()->andReturn(true);
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($imagick);
        $command = new FlipImagick(array('h'));
        $result = $command->execute($image);
        $this->assertTrue($result);

        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('flipimage')->with()->andReturn(true);
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($imagick);
        $command = new FlipImagick(array('v'));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
