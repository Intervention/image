<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Decoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Drivers\Gd\Decoders\BinaryImageDecoder;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Image;
use Intervention\Image\Tests\BaseTestCase;

#[RequiresPhpExtension('gd')]
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
        $image = $this->decoder->decode(file_get_contents($this->getTestResourcePath('tile.png')));
        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals(16, $image->width());
        $this->assertEquals(16, $image->height());
        $this->assertCount(1, $image);
    }

    public function testDecodeGif(): void
    {
        $image = $this->decoder->decode(file_get_contents($this->getTestResourcePath('red.gif')));
        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals(16, $image->width());
        $this->assertEquals(16, $image->height());
        $this->assertCount(1, $image);
    }

    public function testDecodeAnimatedGif(): void
    {
        $image = $this->decoder->decode(file_get_contents($this->getTestResourcePath('cats.gif')));
        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals(75, $image->width());
        $this->assertEquals(50, $image->height());
        $this->assertCount(4, $image);
    }

    public function testDecodeJpegWithExif(): void
    {
        $image = $this->decoder->decode(file_get_contents($this->getTestResourcePath('exif.jpg')));
        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals(16, $image->width());
        $this->assertEquals(16, $image->height());
        $this->assertCount(1, $image);
        $this->assertEquals('Oliver Vogel', $image->exif('IFD0.Artist'));
    }
}
