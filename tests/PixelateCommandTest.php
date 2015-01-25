<?php

use Intervention\Image\Gd\Commands\PixelateCommand as PixelateGd;
use Intervention\Image\Imagick\Commands\PixelateCommand as PixelateImagick;

class PixelateCommandTest extends CommandTestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGd()
    {
        $image = $this->getTestImage('gd');
        $command = new PixelateGd(array(10));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $image = $this->getTestImage('imagick');
        $image->getCore()->shouldReceive('scaleimage')->with(80, 60)->times(3)->andReturn(true);
        $image->getCore()->shouldReceive('scaleimage')->with(800, 600)->times(3)->andReturn(true);
        $image->shouldReceive('getWidth')->once()->andReturn(800);
        $image->shouldReceive('getHeight')->once()->andReturn(600);
        $command = new PixelateImagick(array(10));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
