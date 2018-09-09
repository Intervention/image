<?php

use Intervention\Image\Gd\Commands\TrimCommand as TrimGd;
use Intervention\Image\Imagick\Commands\TrimCommand as TrimImagick;
use PHPUnit\Framework\TestCase;

class TrimCommandTest extends TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testGd()
    {
        $resource = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $image = Mockery::mock('Intervention\Image\Image');
        $baseColor = Mockery::mock('Intervention\Image\Gd\Color');
        $baseColor->shouldReceive('differs')->with($baseColor, 45)->andReturn(true);
        $image->shouldReceive('getCore')->once()->andReturn($resource);
        $image->shouldReceive('getWidth')->once()->andReturn(800);
        $image->shouldReceive('getHeight')->once()->andReturn(600);
        $image->shouldReceive('pickColor')->with(0, 0, 'object')->times(2)->andReturn($baseColor);
        $image->shouldReceive('pickColor')->with(799, 0, 'object')->once()->andReturn($baseColor);
        $image->shouldReceive('setCore')->once();
        $command = new TrimGd(['top-left', ['left', 'right'], 45, 2]);
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $baseColorPixel = new \ImagickPixel;
        $baseColor = Mockery::mock('Intervention\Image\Gd\Color');
        $baseColor->shouldReceive('getPixel')->once()->andReturn($baseColorPixel);
        $imagick = Mockery::mock('Imagick');
        $imagick->width = 100;
        $imagick->height = 100;
        $imagick->shouldReceive('borderimage')->with($baseColorPixel, 1, 1)->once()->andReturn(true);
        $imagick->shouldReceive('trimimage')->with(29632.5)->once()->andReturn(true);
        $imagick->shouldReceive('getimagepage')->once()->andReturn(['x' => 50, 'y' => 50]);
        $imagick->shouldReceive('cropimage')->with(104, 202, 47, 0)->once()->andReturn(true);
        $imagick->shouldReceive('setimagepage')->with(0, 0, 0, 0)->once()->andReturn(true);
        $imagick->shouldReceive('destroy')->with()->once()->andReturn(true);
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getWidth')->once()->andReturn(800);
        $image->shouldReceive('getHeight')->once()->andReturn(600);
        $image->shouldReceive('pickColor')->with(0, 0, 'object')->once()->andReturn($baseColor);
        $image->shouldReceive('getCore')->times(3)->andReturn($imagick);
        $command = new TrimImagick(['top-left', ['left', 'right'], 45, 2]);
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
