<?php

use Intervention\Image\Gd\Commands\LimitColorsCommand as LimitColorsGd;
use Intervention\Image\Imagick\Commands\LimitColorsCommand as LimitColorsImagick;

class LimitColorsCommandTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGd()
    {
        $size = Mockery::mock('\Intervention\Image\Size', array(32, 32));
        $resource = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($resource);
        $image->shouldReceive('setCore')->once();
        $image->shouldReceive('getSize')->once()->andReturn($size);
        $command = new LimitColorsGd(array(16));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $size = Mockery::mock('\Intervention\Image\Size', array(32, 32));
        $imagick = Mockery::mock('\Imagick');
        $imagick->shouldReceive('separateimagechannel')->with(\Imagick::CHANNEL_ALPHA)->times(2);
        $imagick->shouldReceive('transparentpaintimage')->with('#ffffff', 0, 0, false)->once();
        $imagick->shouldReceive('negateimage')->with(false)->once();
        $imagick->shouldReceive('quantizeimage')->with(16, \Imagick::COLORSPACE_RGB, 0, false, false)->once();
        $imagick->shouldReceive('compositeimage')->once();
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getSize')->once()->andReturn($size);
        $image->shouldReceive('getCore')->times(3)->andReturn($imagick);
        $command = new LimitColorsImagick(array(16));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
