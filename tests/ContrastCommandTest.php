<?php

use Intervention\Image\Gd\Commands\ContrastCommand as ContrastGd;
use Intervention\Image\Imagick\Commands\ContrastCommand as ContrastImagick;
use PHPUnit\Framework\TestCase;

class ContrastCommandTest extends TestCase
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
        $command = new ContrastGd([20]);
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('sigmoidalcontrastimage')->with(true, 5, 0)->andReturn(true);
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($imagick);
        $command = new ContrastImagick([20]);
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
