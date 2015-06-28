<?php

use Intervention\Image\Gd\Commands\GetSizeCommand as GetSizeGd;
use Intervention\Image\Imagick\Commands\GetSizeCommand as GetSizeImagick;

class GetsizeCommandTest extends PHPUnit_Framework_TestCase
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
        $command = new GetSizeGd(array());
        $result = $command->execute($image);
        $this->assertTrue($result);
        $this->assertTrue($command->hasOutput());
        $this->assertInstanceOf('Intervention\Image\Size', $command->getOutput());
    }

    public function testImagick()
    {
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('getimagewidth')->with();
        $imagick->shouldReceive('getimageheight')->with();
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($imagick);
        $command = new GetSizeImagick(array());
        $result = $command->execute($image);
        $this->assertTrue($result);
        $this->assertTrue($command->hasOutput());
        $this->assertInstanceOf('Intervention\Image\Size', $command->getOutput());
    }
}
