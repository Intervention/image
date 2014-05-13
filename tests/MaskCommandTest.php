<?php

use Intervention\Image\Gd\Commands\MaskCommand as MaskGd;
use Intervention\Image\Imagick\Commands\MaskCommand as MaskImagick;

class MaskCommandTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testGd()
    {
        $mask_path = __DIR__.'images/star.png';
        $mask_image = Mockery::mock('Intervention\Image\Image');
        $mask_size = Mockery::mock('Intervention\Image\Size', array(32, 32));
        $mask_image->shouldReceive('getSize')->once()->andReturn($mask_size);
        $mask_image->shouldReceive('pickColor')->andReturn(array(0,0,0,0));

        $canvas_image = Mockery::mock('Intervention\Image\Image');
        $canvas_core = imagecreatetruecolor(32, 32);
        $canvas_image->shouldReceive('getCore')->times(2)->andReturn($canvas_core);
        $canvas_image->shouldReceive('pixel');

        $driver = Mockery::mock('Intervention\Image\Gd\Driver');
        $driver->shouldReceive('newImage')->with(32, 32, array(0,0,0,0))->once()->andReturn($canvas_image);
        $driver->shouldReceive('init')->with($mask_path)->once()->andReturn($mask_image);

        $image_size = Mockery::mock('Intervention\Image\Size', array(32, 32));
        $image_core = imagecreatefrompng(__DIR__.'/images/trim.png');
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getSize')->once()->andReturn($image_size);
        $image->shouldReceive('getDriver')->times(2)->andReturn($driver);
        $image->shouldReceive('pickColor')->andReturn(array(0,0,0,0));
        $image->shouldReceive('setCore')->with($canvas_core)->once();

        $command = new MaskGd(array($mask_path, true));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }

    public function testImagick()
    {
        $mask_core = Mockery::mock('Imagick');
        $mask_path = __DIR__.'images/star.png';
        $mask_image = Mockery::mock('Intervention\Image\Image');
        $mask_image->shouldReceive('getCore')->once()->andReturn($mask_core);
        $mask_size = Mockery::mock('Intervention\Image\Size', array(32, 32));
        $mask_image->shouldReceive('getSize')->once()->andReturn($mask_size);

        $driver = Mockery::mock('Intervention\Image\Imagick\Driver');
        $driver->shouldReceive('init')->with($mask_path)->once()->andReturn($mask_image);
        $imagick = Mockery::mock('Imagick');
        $imagick->shouldReceive('setimagematte')->with(true)->once();
        $imagick->shouldReceive('compositeimage')->with($mask_core, \Imagick::COMPOSITE_DSTIN, 0, 0)->once();
        $image = Mockery::mock('Intervention\Image\Image');
        $image->shouldReceive('getCore')->once()->andReturn($imagick);
        $image_size = Mockery::mock('Intervention\Image\Size', array(32, 32));
        $image->shouldReceive('getSize')->once()->andReturn($image_size);
        $image->shouldReceive('getDriver')->once()->andReturn($driver);
        
        $command = new MaskImagick(array($mask_path, true));
        $result = $command->execute($image);
        $this->assertTrue($result);
    }
}
