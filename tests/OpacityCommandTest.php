<?php

use Intervention\Image\Gd\Commands\OpacityCommand as OpacityGd;
use Intervention\Image\Imagick\Commands\OpacityCommand as OpacityImagick;

class OpacityCommandTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGd()
    {
        $mask_core = imagecreatetruecolor(32, 32);
        $mask = Mockery::mock('\Intervention\Image\Image');
        $mask->shouldReceive('getCore')->once()->andReturn($mask_core);

        $resource = imagecreatefrompng(__DIR__.'/images/trim.png');
        $driver = Mockery::mock('\Intervention\Image\Gd\Driver');
        $driver->shouldReceive('newImage')->with(32, 32, 'rgba(0, 0, 0, 0.5)')->andReturn($mask);

        $size = Mockery::mock('\Intervention\Image\Size', array(32, 32));
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getDriver')->once()->andReturn($driver);
        $image->shouldReceive('getSize')->once()->andReturn($size);
        $image->shouldReceive('mask')->with($mask_core, true)->once();
        $command = new OpacityGd(array(50));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('evaluateimage')->with(\Imagick::EVALUATE_DIVIDE, 2, \Imagick::CHANNEL_ALPHA)->andReturn(true);
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($imagick);
        $command = new OpacityImagick(array(50));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
