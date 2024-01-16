<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Imagick\Decoders;

use Intervention\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Drivers\Imagick\Decoders\BinaryImageDecoder;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Image;
use Intervention\Image\Tests\TestCase;
use stdClass;

class BinaryImageDecoderTest extends TestCase
{
    public function testDecodePng(): void
    {
        $decoder = new BinaryImageDecoder();
        $image = $decoder->decode(file_get_contents($this->getTestImagePath('tile.png')));
        $this->assertInstanceOf(Image::class, $image);
        $this->assertInstanceOf(RgbColorspace::class, $image->colorspace());
        $this->assertEquals(16, $image->width());
        $this->assertEquals(16, $image->height());
        $this->assertCount(1, $image);
    }

    public function testDecodeGif(): void
    {
        $decoder = new BinaryImageDecoder();
        $image = $decoder->decode(file_get_contents($this->getTestImagePath('red.gif')));
        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals(16, $image->width());
        $this->assertEquals(16, $image->height());
        $this->assertCount(1, $image);
    }

    public function testDecodeAnimatedGif(): void
    {
        $decoder = new BinaryImageDecoder();
        $image = $decoder->decode(file_get_contents($this->getTestImagePath('cats.gif')));
        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals(75, $image->width());
        $this->assertEquals(50, $image->height());
        $this->assertCount(4, $image);
    }

    public function testDecodeJpegWithExif(): void
    {
        $decoder = new BinaryImageDecoder();
        $image = $decoder->decode(file_get_contents($this->getTestImagePath('exif.jpg')));
        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals(16, $image->width());
        $this->assertEquals(16, $image->height());
        $this->assertCount(1, $image);
        $this->assertEquals('Oliver Vogel', $image->exif('IFD0.Artist'));
    }

    public function testDecodeCmykImage(): void
    {
        $decoder = new BinaryImageDecoder();
        $image = $decoder->decode(file_get_contents($this->getTestImagePath('cmyk.jpg')));
        $this->assertInstanceOf(Image::class, $image);
        $this->assertInstanceOf(CmykColorspace::class, $image->colorspace());
    }

    public function testDecodeNonString(): void
    {
        $decoder = new BinaryImageDecoder();
        $this->expectException(DecoderException::class);
        $decoder->decode(new stdClass());
    }
}
