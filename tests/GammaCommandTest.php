<?php

use Intervention\Image\Gd\Commands\GammaCommand as GammaGd;
use Intervention\Image\Imagick\Commands\GammaCommand as GammaImagick;

class GammaCommandTest extends PHPUnit_Framework_TestCase
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
        $command = new GammaGd(array(1.4));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('gammaimage')->with(1.4)->once()->andReturn(true);
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($imagick);
        $command = new GammaImagick(array(1.4));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
