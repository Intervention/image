<?php

namespace Intervention\Image\Tests;

use Intervention\Image\Colors\Rgb\Colorspace;
use Intervention\Image\EncodedImage;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\CollectionInterface;
use Intervention\Image\Interfaces\CoreInterface;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Modifiers\GreyscaleModifier;
use Mockery;

class ImageTest extends TestCase
{
    protected $modifier_mock;
    protected $encoder_mock;

    public function setUp(): void
    {
        $modifier = Mockery::mock(ModifierInterface::class);
        $modifier->shouldReceive('apply')->andReturn(
            Mockery::mock(ImageInterface::class)
        );

        $encoder = Mockery::mock(EncoderInterface::class);
        $encoder->shouldReceive('encode')->andReturn(
            new EncodedImage('foo')
        );

        $this->encoder_mock = $encoder;
        $this->modifier_mock = $modifier;
    }

    private function testImage(): Image
    {
        $core = Mockery::mock(CoreInterface::class);
        $core->shouldReceive('width')->andReturn(300);
        $core->shouldReceive('height')->andReturn(200);
        $core->shouldReceive('count')->andReturn(12);
        $core->shouldReceive('loops')->andReturn(12);
        $core->shouldReceive('colorspace')->andReturn(new Colorspace());
        $core->shouldReceive('resolve')->andReturn(new GreyscaleModifier());

        $driver = Mockery::mock(DriverInterface::class);
        $driver->shouldReceive('resolve')->with($this->modifier_mock)->andReturn($this->modifier_mock);
        $driver->shouldReceive('resolve')->with($this->encoder_mock)->andReturn($this->encoder_mock);

        $exif = Mockery::mock(CollectionInterface::class);

        return new Image($driver, $core, $exif);
    }

    public function testConstructor(): void
    {
        $image = $this->testImage();
        $this->assertInstanceOf(Image::class, $image);
    }

    public function testDriver(): void
    {
        $image = $this->testImage();
        $this->assertInstanceOf(DriverInterface::class, $image->driver());
    }

    public function testCore(): void
    {
        $image = $this->testImage();
        $this->assertInstanceOf(CoreInterface::class, $image->core());
    }

    public function testWidthHeightSize(): void
    {
        $image = $this->testImage();
        $this->assertEquals(300, $image->width());
        $this->assertEquals(200, $image->height());
        $this->assertInstanceOf(SizeInterface::class, $image->size());
        $this->assertEquals(300, $image->size()->width());
        $this->assertEquals(200, $image->size()->height());
    }

    public function testCount(): void
    {
        $image = $this->testImage();
        $this->assertEquals(12, $image->count());
    }

    public function testGetIterator(): void
    {
        $image = $this->testImage();
        $this->assertInstanceOf(CoreInterface::class, $image->getIterator());
    }

    public function testIsAnimated(): void
    {
        $image = $this->testImage();
        $this->assertTrue($image->isAnimated());
    }

    public function testLoops(): void
    {
        $image = $this->testImage();
        $this->assertEquals(12, $image->loops());
    }

    public function testColorspace(): void
    {
        $image = $this->testImage();
        $this->assertInstanceOf(Colorspace::class, $image->colorspace());
    }

    public function testExif(): void
    {
        $image = $this->testImage();
        $this->assertInstanceOf(CollectionInterface::class, $image->exif());
    }

    public function testModify(): void
    {
        $image = $this->testImage();
        $result = $image->modify($this->modifier_mock);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testEncode(): void
    {
        $image = $this->testImage();
        $result = $image->encode($this->encoder_mock);
        $this->assertInstanceOf(EncodedImageInterface::class, $result);
    }
}
