<?php

use Intervention\Image\Gd\Commands\DestroyCommand as DestroyGd;
use Intervention\Image\Imagick\Commands\DestroyCommand as DestroyImagick;

class DestroyCommandTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGd()
    {
        $resource = imagecreatefrompng(__DIR__.'/images/tile.png');
        $backups = array(
            imagecreatefrompng(__DIR__.'/images/tile.png'), 
            imagecreatefrompng(__DIR__.'/images/tile.png')
        );

        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($resource);
        $image->shouldReceive('getBackups')->once()->andReturn($backups);
        $command = new DestroyGd(array());
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('clear')->with()->andReturn(true);

        $backup = Mockery::mock('Imagick');
        $backup->shouldReceive('clear')->with()->andReturn(true);
        $backups = array($backup);

        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($imagick);
        $image->shouldReceive('getBackups')->once()->andReturn($backups);
        $command = new DestroyImagick(array());
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
