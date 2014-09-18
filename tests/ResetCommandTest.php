<?php

use Intervention\Image\Gd\Commands\ResetCommand as ResetGd;
use Intervention\Image\Imagick\Commands\ResetCommand as ResetImagick;

class ResetCommandTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testGdWithoutName()
    {
        $size = Mockery::mock('Intervention\Image\Size', array(800, 600));
        $resource = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $image = Mockery::mock('Intervention\Image\Image');
        $driver = Mockery::mock('Intervention\Image\Gd\Driver');
        $driver->shouldReceive('cloneCore')->with($resource)->once()->andReturn($resource);
        $image->shouldReceive('getCore')->once()->andReturn($resource);
        $image->shouldReceive('getDriver')->once()->andReturn($driver);
        $image->shouldReceive('setCore')->once();
        $image->shouldReceive('getBackup')->once()->andReturn($resource);
        $command = new ResetGd(array());
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testGdWithName()
    {
        $size = Mockery::mock('Intervention\Image\Size', array(800, 600));
        $resource = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $image = Mockery::mock('Intervention\Image\Image');
        $driver = Mockery::mock('Intervention\Image\Gd\Driver');
        $driver->shouldReceive('cloneCore')->with($resource)->once()->andReturn($resource);
        $image->shouldReceive('getDriver')->once()->andReturn($driver);
        $image->shouldReceive('getCore')->once()->andReturn($resource);
        $image->shouldReceive('setCore')->once();
        $image->shouldReceive('getBackup')->once()->withArgs(array('fooBackup'))->andReturn($resource);
        $command = new ResetGd(array('fooBackup'));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagickWithoutName()
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

    public function testImagickWithName()
    {
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('clear')->once();
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($imagick);
        $image->shouldReceive('setCore')->once();
        $image->shouldReceive('getBackup')->once()->withArgs(array('fooBackup'))->andReturn($imagick);
        $command = new ResetImagick(array('fooBackup'));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
