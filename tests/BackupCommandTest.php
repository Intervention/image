<?php

use Intervention\Image\Gd\Commands\BackupCommand as BackupGd;
use Intervention\Image\Imagick\Commands\BackupCommand as BackupImagick;

class BackupCommandTest extends PHPUnit_Framework_TestCase
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
        $image->shouldReceive('getSize')->once()->andReturn($size);
        $image->shouldReceive('setBackup')->once();
        $command = new BackupGd(array());
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $imagick = Mockery::mock('Imagick');
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($imagick);
        $image->shouldReceive('setBackup')->once();
        $command = new BackupImagick(array());
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
