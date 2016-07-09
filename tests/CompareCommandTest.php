<?php

use Intervention\Image\Gd\Commands\CompareCommand as CompareGd;
use Intervention\Image\Imagick\Commands\CompareCommand as CompareImagick;

class CompareCommandTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testGd()
    {
        $resource = imagecreatefrompng(__DIR__.'/images/gradient.png');
        $image = $this->getMockBuilder('Intervention\Image\Image')
            ->disableOriginalClone()
            ->getMock();
        $image->expects($this->exactly(2))
            ->method('getCore')
            ->will($this->returnValue($resource));

        $otherResource = imagecreatefrompng(__DIR__.'/images/trim.png');
        $otherImage = $this->getMockBuilder('Intervention\Image\Image')
            ->disableOriginalClone()
            ->getMock();
        $otherImage->expects($this->once())
            ->method('getCore')
            ->will($this->returnValue($otherResource));

        $command = new CompareGd(array($otherImage));
        $result = $command->execute($image);

        $this->assertTrue($result);
        $this->assertTrue($command->hasOutput());
        $this->assertInstanceOf('Intervention\Image\Comparison', $command->getOutput());
    }

    public function testImagick()
    {
        $resource = Mockery::mock('Imagick');
        $resource->shouldReceive('getimageblob')->andReturn(file_get_contents(__DIR__.'/images/gradient.png'));
        $resource->shouldReceive('compare')->andReturn([null, 0]);
        $image = $this->getMockBuilder('Intervention\Image\Image')
            ->disableOriginalClone()
            ->getMock();
        $image->expects($this->exactly(2))
            ->method('getCore')
            ->will($this->returnValue($resource));

        $otherResource = Mockery::mock('Imagick');
        $otherImage = $this->getMockBuilder('Intervention\Image\Image')
            ->disableOriginalClone()
            ->getMock();
        $otherImage->expects($this->once())
            ->method('getCore')
            ->will($this->returnValue($otherResource));

        $command = new CompareImagick(array($otherImage));
        $result = $command->execute($image);

        $this->assertTrue($result);
        $this->assertTrue($command->hasOutput());
        $this->assertInstanceOf('Intervention\Image\Comparison', $command->getOutput());
    }
}
