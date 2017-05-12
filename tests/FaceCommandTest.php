<?php

use Intervention\Image\Gd\Commands\FaceCommand as FaceGd;
use Intervention\Image\Imagick\Commands\FaceCommand as FaceImagick;

class FaceCommandTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGd()
    {
        $resource = imagecreatefromjpeg(__DIR__.'/images/face1.jpg');
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($resource);
        $image->shouldReceive('setCore')->once();
        $command = new FaceGd();
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('cropimage')->with(100, 150, 10, 20)->andReturn(true);
        $imagick->shouldReceive('setimagepage')->with(0, 0, 0, 0)->once();
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->times(2)->andReturn($imagick);
        $command = new FaceImagick();
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
