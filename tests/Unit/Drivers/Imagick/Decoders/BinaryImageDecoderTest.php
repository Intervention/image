<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Decoders;

use Intervention\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Drivers\Imagick\Decoders\BinaryImageDecoder;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Image;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use stdClass;

#[RequiresPhpExtension('imagick')]
#[CoversClass(BinaryImageDecoder::class)]
final class BinaryImageDecoderTest extends BaseTestCase
{
    protected BinaryImageDecoder $decoder;

    protected function setUp(): void
    {
        $this->decoder = new BinaryImageDecoder();
        $this->decoder->setDriver(new Driver());
    }

    public function testDecodePng(): void
    {
        $image = $this->decoder->decode(file_get_contents(Resource::create('tile.png')->path()));
        $this->assertInstanceOf(Image::class, $image);
        $this->assertInstanceOf(RgbColorspace::class, $image->colorspace());
        $this->assertEquals(16, $image->width());
        $this->assertEquals(16, $image->height());
        $this->assertCount(1, $image);
    }

    public function testDecodeGif(): void
    {
        $image = $this->decoder->decode(file_get_contents(Resource::create('red.gif')->path()));
        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals(16, $image->width());
        $this->assertEquals(16, $image->height());
        $this->assertCount(1, $image);
    }

    public function testDecodeAnimatedGif(): void
    {
        $image = $this->decoder->decode(file_get_contents(Resource::create('cats.gif')->path()));
        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals(75, $image->width());
        $this->assertEquals(50, $image->height());
        $this->assertCount(4, $image);
    }

    public function testDecodeJpegWithExif(): void
    {
        $image = $this->decoder->decode(file_get_contents(Resource::create('exif.jpg')->path()));
        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals(16, $image->width());
        $this->assertEquals(16, $image->height());
        $this->assertCount(1, $image);
        $this->assertEquals('Oliver Vogel', $image->exif('IFD0.Artist'));
    }

    public function testDecodeCmykImage(): void
    {
        $image = $this->decoder->decode(file_get_contents(Resource::create('cmyk.jpg')->path()));
        $this->assertInstanceOf(Image::class, $image);
        $this->assertInstanceOf(CmykColorspace::class, $image->colorspace());
    }

    public function testDecodeStringable(): void
    {
        $image = $this->decoder->decode(Resource::create('tile.png')->stringableData());
        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals(16, $image->width());
        $this->assertEquals(16, $image->height());
        $this->assertCount(1, $image);
    }

    public function testDecodeNonString(): void
    {
        $this->expectException(DecoderException::class);
        $this->decoder->decode(new stdClass());
    }
}
