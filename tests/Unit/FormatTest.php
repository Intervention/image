<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Intervention\Image\Encoders\AvifEncoder;
use Intervention\Image\Encoders\BmpEncoder;
use Intervention\Image\Encoders\GifEncoder;
use Intervention\Image\Encoders\HeicEncoder;
use Intervention\Image\Encoders\Jpeg2000Encoder;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Encoders\TiffEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Format;
use Intervention\Image\Tests\BaseTestCase;

final class FormatTest extends BaseTestCase
{
    public function testMediaTypesJpeg(): void
    {
        $format = Format::JPEG;
        $mediaTypes = $format->mediaTypes();
        $this->assertIsArray($mediaTypes);
        $this->assertCount(3, $mediaTypes);
    }

    public function testMediaTypesWebp(): void
    {
        $format = Format::WEBP;
        $mediaTypes = $format->mediaTypes();
        $this->assertIsArray($mediaTypes);
        $this->assertCount(2, $mediaTypes);
    }

    public function testMediaTypesFGif(): void
    {
        $format = Format::GIF;
        $mediaTypes = $format->mediaTypes();
        $this->assertIsArray($mediaTypes);
        $this->assertCount(1, $mediaTypes);
    }

    public function testMediaTypesPng(): void
    {
        $format = Format::PNG;
        $mediaTypes = $format->mediaTypes();
        $this->assertIsArray($mediaTypes);
        $this->assertCount(2, $mediaTypes);
    }

    public function testMediaTypesAvif(): void
    {
        $format = Format::AVIF;
        $mediaTypes = $format->mediaTypes();
        $this->assertIsArray($mediaTypes);
        $this->assertCount(2, $mediaTypes);
    }

    public function testMediaTypesBmp(): void
    {
        $format = Format::BMP;
        $mediaTypes = $format->mediaTypes();
        $this->assertIsArray($mediaTypes);
        $this->assertCount(8, $mediaTypes);
    }

    public function testMediaTypesTiff(): void
    {
        $format = Format::TIFF;
        $mediaTypes = $format->mediaTypes();
        $this->assertIsArray($mediaTypes);
        $this->assertCount(1, $mediaTypes);
    }

    public function testMediaTypesJpeg2000(): void
    {
        $format = Format::JPEG2000;
        $mediaTypes = $format->mediaTypes();
        $this->assertIsArray($mediaTypes);
        $this->assertCount(3, $mediaTypes);
    }

    public function testMediaTypesHeic(): void
    {
        $format = Format::HEIC;
        $mediaTypes = $format->mediaTypes();
        $this->assertIsArray($mediaTypes);
        $this->assertCount(2, $mediaTypes);
    }

    public function testEncoderJpeg(): void
    {
        $format = Format::JPEG;
        $this->assertInstanceOf(JpegEncoder::class, $format->encoder());
    }

    public function testEncoderAvif(): void
    {
        $format = Format::AVIF;
        $this->assertInstanceOf(AvifEncoder::class, $format->encoder());
    }

    public function testEncoderWebp(): void
    {
        $format = Format::WEBP;
        $this->assertInstanceOf(WebpEncoder::class, $format->encoder());
    }

    public function testEncoderGif(): void
    {
        $format = Format::GIF;
        $this->assertInstanceOf(GifEncoder::class, $format->encoder());
    }

    public function testEncoderPng(): void
    {
        $format = Format::PNG;
        $this->assertInstanceOf(PngEncoder::class, $format->encoder());
    }

    public function testEncoderBitmap(): void
    {
        $format = Format::BMP;
        $this->assertInstanceOf(BmpEncoder::class, $format->encoder());
    }

    public function testEncoderTiff(): void
    {
        $format = Format::TIFF;
        $this->assertInstanceOf(TiffEncoder::class, $format->encoder());
    }

    public function testEncoderJpep2000(): void
    {
        $format = Format::JPEG2000;
        $this->assertInstanceOf(Jpeg2000Encoder::class, $format->encoder());
    }

    public function testEncoderHeic(): void
    {
        $format = Format::HEIC;
        $this->assertInstanceOf(HeicEncoder::class, $format->encoder());
    }
}
