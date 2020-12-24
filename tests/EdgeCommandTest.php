<?php

use Intervention\Image\Gd\Commands\EdgeCommand as EdgeCommandGd;
use Intervention\Image\Imagick\Commands\EdgeCommand as EdgeCommandImagick;
use PHPUnit\Framework\TestCase;

class EdgeCommandTest extends TestCase
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
        $command = new EdgeCommandGd([]);
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('edgeimage')->andReturn(true);
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($imagick);
        $command = new EdgeCommandImagick([]);
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
