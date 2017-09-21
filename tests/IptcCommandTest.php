<?php

use Intervention\Image\Commands\IptcCommand;

class IptcCommandTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testFetchAll()
    {
        $image = Mockery::mock('Intervention\Image\Image');
        $image->dirname = __DIR__.'/images';
        $image->basename = 'iptc.jpg';
        $command = new IptcCommand([]);
        $result = $command->execute($image);
        $this->assertTrue($result);
        $this->assertTrue($command->hasOutput());
        $this->assertInternalType('array', $command->getOutput());
    }

    public function testFetchDefined()
    {
        $image = Mockery::mock('Intervention\Image\Image');
        $image->dirname = __DIR__.'/images';
        $image->basename = 'exif.jpg';
        $command = new IptcCommand(['AuthorByline']);
        $result = $command->execute($image);
        $this->assertTrue($result);
        $this->assertTrue($command->hasOutput());
        $this->assertEquals('Oliver Vogel', $command->getOutput());
    }


    public function testFetchNonExisting()
    {
        $image = Mockery::mock('Intervention\Image\Image');
        $image->dirname = __DIR__.'/images';
        $image->basename = 'exif.jpg';
        $command = new IptcCommand(['xxx']);
        $result = $command->execute($image);
        $this->assertTrue($result);
        $this->assertTrue($command->hasOutput());
        $this->assertEquals(null, $command->getOutput());
    }


    public function testFetchFromPng()
    {
        $image = Mockery::mock('Intervention\Image\Image');
        $image->dirname = __DIR__.'/images';
        $image->basename = 'star.png';
        $command = new IptcCommand(['Orientation']);
        $result = $command->execute($image);
        $this->assertTrue($result);
        $this->assertTrue($command->hasOutput());
        $this->assertEquals(null, $command->getOutput());
    }

    public function testReturnNullOnIptcReadFail()
    {
        $image = Mockery::mock('Intervention\Image\Image');
        $command = new IptcCommand(['Orientation']);
        $result = $command->execute($image);
        $this->assertTrue($result);
        $this->assertTrue($command->hasOutput());
        $this->assertEquals(null, $command->getOutput());
    }
}
