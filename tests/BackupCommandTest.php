<?php

use Intervention\Image\Gd\Commands\BackupCommand as BackupGd;
use Intervention\Image\Imagick\Commands\BackupCommand as BackupImagick;
use PHPUnit\Framework\TestCase;

class BackupCommandTest extends TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testGdWithoutName()
    {
        $driver = Mockery::mock('Intervention\Image\Gd\Driver');
        $driver->shouldReceive('cloneCore')->once();
        $resource = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $image = Mockery::mock('Intervention\Image\Image',  [$driver]);
        $image->shouldReceive('getCore')->once()->andReturn($resource);
        $image->shouldReceive('setBackup')->once();
        $command = new BackupGd([]);
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testGdWithName()
    {
        $driver = Mockery::mock('Intervention\Image\Gd\Driver');
        $driver->shouldReceive('cloneCore')->once();
        $resource = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $image = Mockery::mock('Intervention\Image\Image', [$driver]);
        $image->shouldReceive('getCore')->once()->andReturn($resource);
        $image->shouldReceive('setBackup')->once();
        $command = new BackupGd(['name' => 'fooBackup']);
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagickWithoutName()
    {
        $driver = Mockery::mock('Intervention\Image\Imagick\Driver');
        $driver->shouldReceive('cloneCore')->once();
        $imagick = Mockery::mock('Imagick');
        $image = Mockery::mock('Intervention\Image\Image', [$driver]);
        $image->shouldReceive('getCore')->once()->andReturn($imagick);
        $image->shouldReceive('setBackup')->once();
        $command = new BackupImagick([]);
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagickWithName()
    {
        $driver = Mockery::mock('Intervention\Image\Imagick\Driver');
        $driver->shouldReceive('cloneCore')->once();
        $imagick = Mockery::mock('Imagick');
        $image = Mockery::mock('Intervention\Image\Image', [$driver]);
        $image->shouldReceive('getCore')->once()->andReturn($imagick);
        $image->shouldReceive('setBackup')->once();
        $command = new BackupImagick(['name' => 'fooBackup']);
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
