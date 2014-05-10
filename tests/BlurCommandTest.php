<?php

use Intervention\Image\Gd\Commands\BlurCommand as BlurGd;
use Intervention\Image\Imagick\Commands\BlurCommand as BlurImagick;

class BlurCommandTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGd()
    {
        $resource = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->times(2)->andReturn($resource);
        $command = new BlurGd(array(2));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('blurimage')->with(2, 1)->andReturn(true);
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($imagick);
        $command = new BlurImagick(array(2));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
