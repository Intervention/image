<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Decoders;

use Intervention\Image\Drivers\Gd\Decoders\BinaryImageDecoder;
use Intervention\Image\Image;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Drivers\Gd\Decoders\BinaryImageDecoder
 */
class BinaryImageDecoderTest extends TestCase
{
    public function testDecodePng(): void
    {
        $decoder = new BinaryImageDecoder();
        $image = $decoder->decode(file_get_contents($this->getTestImagePath('tile.png')));
        $this->assertInstanceOf(Image::class, $image);
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
}
