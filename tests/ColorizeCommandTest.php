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
        $image->shouldReceive('getCore')->once()->andReturn($resource);
        $command = new ColorizeGd(array(20, 0, -40));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('getquantumrange')->with()->once()->andReturn(array('quantumRangeLong' => 42));
        $imagick->shouldReceive('levelimage')->with(0, 4, 42, \Imagick::CHANNEL_RED)->once()->andReturn(true);
        $imagick->shouldReceive('levelimage')->with(0, 1, 42, \Imagick::CHANNEL_GREEN)->once()->andReturn(true);
        $imagick->shouldReceive('levelimage')->with(0, 0.6, 42, \Imagick::CHANNEL_BLUE)->once()->andReturn(true);
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->times(4)->andReturn($imagick);
        $command = new ColorizeImagick(array(20, 0, -40));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
