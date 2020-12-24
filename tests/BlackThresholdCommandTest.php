<?php

use Intervention\Image\Gd\Commands\BlackThresholdCommand as BlackThresholdCommandGd;
use Intervention\Image\Imagick\Commands\BlackThresholdCommand as BlackThresholdCommandImagick;
use PHPUnit\Framework\TestCase;

class BlackThresholdCommandTest extends TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testGd()
    {
        $resource = imagecreatefromjpeg(__DIR__.'/images/test.jpg');
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($resource);
        $command = new BlackThresholdCommandGd([30]);
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('blackthresholdimage')->andReturn(true);
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($imagick);
        $command = new BlackThresholdCommandImagick([30]);
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
