<?php

use Intervention\Image\Gd\Commands\ResetCommand as ResetGd;
use Intervention\Image\Imagick\Commands\ResetCommand as ResetImagick;

class ResetCommandTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGd()
    {
        $size = Mockery::mock('Intervention\Image\Size', array(800, 600));
        $resource = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($resource);
        $image->shouldReceive('setCore')->once();
        $image->shouldReceive('getBackup')->once()->andReturn($resource);
        $command = new ResetGd(array());
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('clear')->once();
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($imagick);
        $image->shouldReceive('setCore')->once();
        $image->shouldReceive('getBackup')->once()->andReturn($imagick);
        $command = new ResetImagick(array());
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
