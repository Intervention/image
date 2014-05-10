<?php

use Intervention\Image\Gd\Commands\ContrastCommand as ContrastGd;
use Intervention\Image\Imagick\Commands\ContrastCommand as ContrastImagick;

class ContrastCommandTest extends PHPUnit_Framework_TestCase
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
        $command = new ContrastGd(array(20));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('sigmoidalcontrastimage')->with(true, 5, 0)->andReturn(true);
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($imagick);
        $command = new ContrastImagick(array(20));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
