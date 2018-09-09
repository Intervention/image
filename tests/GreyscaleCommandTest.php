<?php

use Intervention\Image\Gd\Commands\GreyscaleCommand as GreyscaleGd;
use Intervention\Image\Imagick\Commands\GreyscaleCommand as GreyscaleImagick;
use PHPUnit\Framework\TestCase;

class GreyscaleCommandTest extends TestCase
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
        $command = new GreyscaleGd([]);
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('modulateimage')->with(100, 0, 100)->andReturn(true);
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($imagick);
        $command = new GreyscaleImagick([]);
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
