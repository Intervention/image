<?php

use Intervention\Image\Gd\Driver as GdDriver;
use Intervention\Image\Imagick\Driver as ImagickDriver;

class DriverTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testNewImageGd()
    {
        $driver = new GdDriver(
            Mockery::mock('\Intervention\Image\Gd\Decoder'),
            Mockery::mock('\Intervention\Image\Gd\Encoder')
        );

        $image = $driver->newImage(300, 200, '00ff00');
        $this->assertInstanceOf('\Intervention\Image\Image', $image);
        $this->assertInstanceOf('\Intervention\Image\Gd\Driver', $image->getDriver());
        $this->assertInternalType('resource', $image->getCore());
    }

    public function testNewImageImagick()
    {
        $driver = new ImagickDriver(
            Mockery::mock('\Intervention\Image\Imagick\Decoder'),
            Mockery::mock('\Intervention\Image\Imagick\Encoder')
        );

        $image = $driver->newImage(300, 200, '00ff00');
        $this->assertInstanceOf('\Intervention\Image\Image', $image);
        $this->assertInstanceOf('\Intervention\Image\Imagick\Driver', $image->getDriver());
        $this->assertInstanceOf('\Imagick', $image->getCore());
    }

    public function testParseColorGd()
    {
        $driver = new GdDriver(
            Mockery::mock('\Intervention\Image\Gd\Decoder'),
            Mockery::mock('\Intervention\Image\Gd\Encoder')
        );

        $color = $driver->parseColor('00ff00');
        $this->assertInstanceOf('\Intervention\Image\Gd\Color', $color);
    }

    public function testParseColorImagick()
    {
        $driver = new ImagickDriver(
            Mockery::mock('\Intervention\Image\Imagick\Decoder'),
            Mockery::mock('\Intervention\Image\Imagick\Encoder')
        );

        $color = $driver->parseColor('00ff00');
        $this->assertInstanceOf('\Intervention\Image\Imagick\Color', $color);
    }
}
