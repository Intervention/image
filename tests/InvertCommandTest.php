<?php

use Intervention\Image\Gd\Commands\InvertCommand as InvertGd;
use Intervention\Image\Imagick\Commands\InvertCommand as InvertImagick;
use PHPUnit\Framework\TestCase;

class InvertCommandTest extends TestCase
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
        $command = new InvertGd([]);
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('negateimage')->with(false)->andReturn(true);
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($imagick);
        $command = new InvertImagick([]);
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
