<?php

use Intervention\Image\Image;
use Intervention\Image\Commands\ExifCommand;

class ExifCommandTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testFetchAll()
    {
        $image = new Image;
        $image->dirname = __DIR__.'/images';
        $image->basename = 'exif.jpg';
        $command = new ExifCommand(array());
        $result = $command->execute($image);
        $this->assertTrue($result);
        $this->assertTrue($command->hasOutput());
        $this->assertInternalType('array', $command->getOutput());
    }

    public function testFetchDefined()
    {
        $image = new Image;
        $image->dirname = __DIR__.'/images';
        $image->basename = 'exif.jpg';
        $command = new ExifCommand(array('Artist'));
        $result = $command->execute($image);
        $this->assertTrue($result);
        $this->assertTrue($command->hasOutput());
        $this->assertEquals('Oliver Vogel', $command->getOutput());
    }

    public function testFetchNonExisting()
    {
        $image = new Image;
        $image->dirname = __DIR__.'/images';
        $image->basename = 'exif.jpg';
        $command = new ExifCommand(array('xxx'));
        $result = $command->execute($image);
        $this->assertTrue($result);
        $this->assertTrue($command->hasOutput());
        $this->assertEquals(null, $command->getOutput());
    }

    public function testFetchFromPng()
    {
        $image = new Image;
        $image->dirname = __DIR__.'/images';
        $image->basename = 'star.png';
        $command = new ExifCommand(array('Orientation'));
        $result = $command->execute($image);
        $this->assertTrue($result);
        $this->assertTrue($command->hasOutput());
        $this->assertEquals(null, $command->getOutput());
    }

    public function testImagickFetchAll()
    {
        $image = $this->imagick()->make(__DIR__.'/images/exif.jpg');
        $command = new \Intervention\Image\Imagick\Commands\ExifCommand(array());
        $command->dontPreferExtension();
        $result = $command->execute($image);
        $this->assertTrue($result);
        $this->assertTrue($command->hasOutput());
        $this->assertInternalType('array', $command->getOutput());
        $this->assertEquals('Oliver Vogel', $command->getOutput()['Artist']);
    }

    public function testImagickFetchDefined()
    {
        $image = $this->imagick()->make(__DIR__.'/images/exif.jpg');
        $command = new \Intervention\Image\Imagick\Commands\ExifCommand(array('Artist'));
        $command->dontPreferExtension();
        $result = $command->execute($image);
        $this->assertTrue($result);
        $this->assertTrue($command->hasOutput());
        $this->assertEquals('Oliver Vogel', $command->getOutput());
    }

    public function testImagickNonExisting()
    {
        $image = $this->imagick()->make(__DIR__.'/images/exif.jpg');
        $command = new \Intervention\Image\Imagick\Commands\ExifCommand(array('xx'));
        $command->dontPreferExtension();
        $result = $command->execute($image);
        $this->assertTrue($result);
        $this->assertTrue($command->hasOutput());
        $this->assertEquals(null, $command->getOutput());
    }

    public function testImagickFallbackToExifExtenstion()
    {
        $image = $this->imagick()->make(__DIR__.'/images/exif.jpg');
        $command = new \Intervention\Image\Imagick\Commands\ExifCommand(array('Artist'));
        $result = $command->execute($image);
        $this->assertTrue($result);
        $this->assertTrue($command->hasOutput());
        $this->assertEquals('Oliver Vogel', $command->getOutput());
    }

    private function imagick()
    {
        return new \Intervention\Image\ImageManager(array(
            'driver' => 'imagick'
        ));
    }
}
