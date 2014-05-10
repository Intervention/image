<?php

use Intervention\Image\Gd\Commands\DestroyCommand as DestroyGd;
use Intervention\Image\Imagick\Commands\DestroyCommand as DestroyImagick;

class DestroyCommandTest extends PHPUnit_Framework_TestCase
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
        $command = new DestroyGd(array());
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('clear')->with()->andReturn(true);
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($imagick);
        $command = new DestroyImagick(array());
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
