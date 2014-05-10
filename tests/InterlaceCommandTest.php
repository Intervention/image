<?php

use Intervention\Image\Gd\Commands\InterlaceCommand as InterlaceGd;
use Intervention\Image\Imagick\Commands\InterlaceCommand as InterlaceImagick;

class InterlaceCommandTest extends PHPUnit_Framework_TestCase
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
        $command = new InterlaceGd(array(true));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('setinterlacescheme')->with(\Imagick::INTERLACE_LINE)->andReturn(true);
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($imagick);
        $command = new InterlaceImagick(array(true));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
