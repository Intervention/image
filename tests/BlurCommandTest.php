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
        $frame = Mockery::mock('Intervention\Image\Frame');
        $container = Mockery::mock('Intervention\Image\Gd\Container');
        $iterator = new ArrayIterator(array($frame));
        $container->shouldReceive('getIterator')->andReturn($iterator);
        $frame->shouldReceive('getCore')->andReturn($resource);
        $image->shouldReceive('getIterator')->andReturn($container);
        $command = new BlurGd(array(2));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('blurimage')->with(2, 1)->once()->andReturn(true);
        $imagick->shouldReceive('rewind');
        $imagick->shouldReceive('current')->andReturn($imagick);
        $imagick->shouldReceive('valid')->andReturn(true);
        $iterator = new ArrayIterator(array($imagick));

        $image = Mockery::mock('Intervention\Image\Image');
        $container = Mockery::mock('Intervention\Image\Imagick\Container');
        $container->shouldReceive('getIterator')->once()->andReturn($iterator);
        $image->shouldReceive('getIterator')->once()->andReturn($container);
        $command = new BlurImagick(array(2));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
