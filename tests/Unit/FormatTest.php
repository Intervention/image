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
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\FileExtension;
use Intervention\Image\Format;
use Intervention\Image\MediaType;
use Intervention\Image\Tests\BaseTestCase;

final class FormatTest extends BaseTestCase
{
    public function testCreate(): void
    {
        $this->assertEquals(Format::JPEG, Format::create(Format::JPEG));
        $this->assertEquals(Format::JPEG, Format::create('jpg'));
        $this->assertEquals(Format::JPEG, Format::create('jpeg'));
        $this->assertEquals(Format::JPEG, Format::create('image/jpeg'));
        $this->assertEquals(Format::GIF, Format::create('image/gif'));
        $this->assertEquals(Format::PNG, Format::create(FileExtension::PNG));
        $this->assertEquals(Format::WEBP, Format::create(MediaType::IMAGE_WEBP));
    }

    public function testCreateUnknown(): void
    {
        $this->expectException(NotSupportedException::class);
        Format::create('foo');
    }

    public function testTryCreate(): void
    {
        $this->assertEquals(Format::JPEG, Format::tryCreate(Format::JPEG));
        $this->assertEquals(Format::JPEG, Format::tryCreate('jpg'));
        $this->assertEquals(Format::JPEG, Format::tryCreate('jpeg'));
        $this->assertEquals(Format::JPEG, Format::tryCreate('image/jpeg'));
        $this->assertEquals(Format::GIF, Format::tryCreate('image/gif'));
        $this->assertEquals(Format::PNG, Format::tryCreate(FileExtension::PNG));
        $this->assertEquals(Format::WEBP, Format::tryCreate(MediaType::IMAGE_WEBP));
        $this->assertNull(Format::tryCreate('no-format'));
    }

    public function testMediaTypesJpeg(): void
    {
        $format = Format::JPEG;
        $mediaTypes = $format->mediaTypes();
        $this->assertIsArray($mediaTypes);
        $this->assertCount(4, $mediaTypes);

        $this->assertEquals(MediaType::IMAGE_JPEG, $format->mediaType());
    }

    public function testMediaTypesWebp(): void
    {
        $format = Format::WEBP;
        $mediaTypes = $format->mediaTypes();
        $this->assertIsArray($mediaTypes);
        $this->assertCount(2, $mediaTypes);

        $this->assertEquals(MediaType::IMAGE_WEBP, $format->mediaType());
    }

    public function testMediaTypesFGif(): void
    {
        $format = Format::GIF;
        $mediaTypes = $format->mediaTypes();
        $this->assertIsArray($mediaTypes);
        $this->assertCount(1, $mediaTypes);

        $this->assertEquals(MediaType::IMAGE_GIF, $format->mediaType());
    }

    public function testMediaTypesPng(): void
    {
        $format = Format::PNG;
        $mediaTypes = $format->mediaTypes();
        $this->assertIsArray($mediaTypes);
        $this->assertCount(2, $mediaTypes);

        $this->assertEquals(MediaType::IMAGE_PNG, $format->mediaType());
    }

    public function testMediaTypesAvif(): void
    {
        $format = Format::AVIF;
        $mediaTypes = $format->mediaTypes();
        $this->assertIsArray($mediaTypes);
        $this->assertCount(2, $mediaTypes);

        $this->assertEquals(MediaType::IMAGE_AVIF, $format->mediaType());
    }

    public function testMediaTypesBmp(): void
    {
        $format = Format::BMP;
        $mediaTypes = $format->mediaTypes();
        $this->assertIsArray($mediaTypes);
        $this->assertCount(8, $mediaTypes);

        $this->assertEquals(MediaType::IMAGE_BMP, $format->mediaType());
    }

    public function testMediaTypesTiff(): void
    {
        $format = Format::TIFF;
        $mediaTypes = $format->mediaTypes();
        $this->assertIsArray($mediaTypes);
        $this->assertCount(1, $mediaTypes);

        $this->assertEquals(MediaType::IMAGE_TIFF, $format->mediaType());
    }

    public function testMediaTypesJpeg2000(): void
    {
        $format = Format::JP2;
        $mediaTypes = $format->mediaTypes();
        $this->assertIsArray($mediaTypes);
        $this->assertCount(3, $mediaTypes);

        $this->assertEquals(MediaType::IMAGE_JP2, $format->mediaType());
    }

    public function testMediaTypesHeic(): void
    {
        $format = Format::HEIC;
        $mediaTypes = $format->mediaTypes();
        $this->assertIsArray($mediaTypes);
        $this->assertCount(3, $mediaTypes);

        $this->assertEquals(MediaType::IMAGE_HEIC, $format->mediaType());
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
        $format = Format::JP2;
        $this->assertInstanceOf(Jpeg2000Encoder::class, $format->encoder());
    }

    public function testEncoderHeic(): void
    {
        $format = Format::HEIC;
        $this->assertInstanceOf(HeicEncoder::class, $format->encoder());
    }

    public function testFileExtensionsJpeg(): void
    {
        $format = Format::JPEG;
        $extensions = $format->fileExtensions();
        $this->assertIsArray($extensions);
        $this->assertCount(2, $extensions);

        $this->assertEquals(FileExtension::JPG, $format->fileExtension());
    }

    public function testFileExtensionsWebp(): void
    {
        $format = Format::WEBP;
        $extensions = $format->fileExtensions();
        $this->assertIsArray($extensions);
        $this->assertCount(1, $extensions);

        $this->assertEquals(FileExtension::WEBP, $format->fileExtension());
    }

    public function testFileExtensionsGif(): void
    {
        $format = Format::GIF;
        $extensions = $format->fileExtensions();
        $this->assertIsArray($extensions);
        $this->assertCount(1, $extensions);

        $this->assertEquals(FileExtension::GIF, $format->fileExtension());
    }

    public function testFileExtensionsPng(): void
    {
        $format = Format::PNG;
        $extensions = $format->fileExtensions();
        $this->assertIsArray($extensions);
        $this->assertCount(1, $extensions);

        $this->assertEquals(FileExtension::PNG, $format->fileExtension());
    }

    public function testFileExtensionsAvif(): void
    {
        $format = Format::AVIF;
        $extensions = $format->fileExtensions();
        $this->assertIsArray($extensions);
        $this->assertCount(1, $extensions);

        $this->assertEquals(FileExtension::AVIF, $format->fileExtension());
    }

    public function testFileExtensionsBmp(): void
    {
        $format = Format::BMP;
        $extensions = $format->fileExtensions();
        $this->assertIsArray($extensions);
        $this->assertCount(1, $extensions);

        $this->assertEquals(FileExtension::BMP, $format->fileExtension());
    }

    public function testFileExtensionsTiff(): void
    {
        $format = Format::TIFF;
        $extensions = $format->fileExtensions();
        $this->assertIsArray($extensions);
        $this->assertCount(2, $extensions);

        $this->assertEquals(FileExtension::TIF, $format->fileExtension());
    }

    public function testFileExtensionsJp2(): void
    {
        $format = Format::JP2;
        $extensions = $format->fileExtensions();
        $this->assertIsArray($extensions);
        $this->assertCount(8, $extensions);

        $this->assertEquals(FileExtension::JP2, $format->fileExtension());
    }

    public function testFileExtensionsHeic(): void
    {
        $format = Format::HEIC;
        $extensions = $format->fileExtensions();
        $this->assertIsArray($extensions);
        $this->assertCount(2, $extensions);

        $this->assertEquals(FileExtension::HEIC, $format->fileExtension());
    }
}
