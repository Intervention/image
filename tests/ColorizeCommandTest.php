<?php

use Intervention\Image\Gd\Commands\ColorizeCommand as ColorizeGd;
use Intervention\Image\Imagick\Commands\ColorizeCommand as ColorizeImagick;

class ColorizeCommandTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGd()
    {
        $resource = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $image = Mockery::mock('Intervention\Image\Image');
        $frame = Mockery::mock('Intervention\Image\Frame');
        $container = Mockery::mock('Intervention\Image\Gd\Container');
        $iterator = new ArrayIterator(array($frame));
        $container->shouldReceive('getIterator')->andReturn($iterator);
        $frame->shouldReceive('getCore')->andReturn($resource);
        $image->shouldReceive('getIterator')->andReturn($container);

        $command = new ColorizeGd(array(20, 0, -40));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $imagick = Mockery::mock('Imagick');
        $iterator = new ArrayIterator(array($imagick));
        $imagick->shouldReceive('getquantumrange')->with()->once()->andReturn(array('quantumRangeLong' => 42));
        $imagick->shouldReceive('levelimage')->with(0, 4, 42, \Imagick::CHANNEL_RED)->once()->andReturn(true);
        $imagick->shouldReceive('levelimage')->with(0, 1, 42, \Imagick::CHANNEL_GREEN)->once()->andReturn(true);
        $imagick->shouldReceive('levelimage')->with(0, 0.6, 42, \Imagick::CHANNEL_BLUE)->once()->andReturn(true);
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getIterator')->andReturn($iterator);
        
        $command = new ColorizeImagick(array(20, 0, -40));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
