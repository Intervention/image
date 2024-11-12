<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Generator;
use Intervention\Image\FileExtension;
use Intervention\Image\Format;
use Intervention\Image\MediaType;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class MediaTypeTest extends BaseTestCase
{
    public function testFormatJpeg(): void
    {
        $mime = MediaType::IMAGE_JPEG;
        $this->assertEquals(Format::JPEG, $mime->format());

        $mime = MediaType::IMAGE_PJPEG;
        $this->assertEquals(Format::JPEG, $mime->format());

        $mime = MediaType::IMAGE_JPG;
        $this->assertEquals(Format::JPEG, $mime->format());

        $mime = MediaType::IMAGE_X_JPEG;
        $this->assertEquals(Format::JPEG, $mime->format());
    }

    public function testFormatWebp(): void
    {
        $mime = MediaType::IMAGE_WEBP;
        $this->assertEquals(Format::WEBP, $mime->format());

        $mime = MediaType::IMAGE_X_WEBP;
        $this->assertEquals(Format::WEBP, $mime->format());
    }

    public function testFormatGif(): void
    {
        $mime = MediaType::IMAGE_GIF;
        $this->assertEquals(Format::GIF, $mime->format());
    }

    public function testFormatPng(): void
    {
        $mime = MediaType::IMAGE_PNG;
        $this->assertEquals(Format::PNG, $mime->format());

        $mime = MediaType::IMAGE_X_PNG;
        $this->assertEquals(Format::PNG, $mime->format());
    }

    public function testFormatAvif(): void
    {
        $mime = MediaType::IMAGE_AVIF;
        $this->assertEquals(Format::AVIF, $mime->format());

        $mime = MediaType::IMAGE_X_AVIF;
        $this->assertEquals(Format::AVIF, $mime->format());
    }

    public function testFormatBmp(): void
    {
        $mime = MediaType::IMAGE_BMP;
        $this->assertEquals(Format::BMP, $mime->format());

        $mime = MediaType::IMAGE_X_BMP;
        $this->assertEquals(Format::BMP, $mime->format());

        $mime = MediaType::IMAGE_X_BITMAP;
        $this->assertEquals(Format::BMP, $mime->format());

        $mime = MediaType::IMAGE_X_WIN_BITMAP;
        $this->assertEquals(Format::BMP, $mime->format());

        $mime = MediaType::IMAGE_X_WINDOWS_BMP;
        $this->assertEquals(Format::BMP, $mime->format());
    }

    public function testFormatTiff(): void
    {
        $mime = MediaType::IMAGE_TIFF;
        $this->assertEquals(Format::TIFF, $mime->format());
    }

    public function testFormatJpeg2000(): void
    {
        $mime = MediaType::IMAGE_JPM;
        $this->assertEquals(Format::JP2, $mime->format());

        $mime = MediaType::IMAGE_JPX;
        $this->assertEquals(Format::JP2, $mime->format());

        $mime = MediaType::IMAGE_JP2;
        $this->assertEquals(Format::JP2, $mime->format());
    }

    public function testFormatHeic(): void
    {
        $mime = MediaType::IMAGE_HEIC;
        $this->assertEquals(Format::HEIC, $mime->format());

        $mime = MediaType::IMAGE_X_HEIC;
        $this->assertEquals(Format::HEIC, $mime->format());

        $mime = MediaType::IMAGE_HEIF;
        $this->assertEquals(Format::HEIC, $mime->format());
    }

    #[DataProvider('fileExtensionsDataProvider')]
    public function testFileExtensions(
        MediaType $mediaType,
        int $fileExtensionCount,
        FileExtension $fileExtension
    ): void {
        $this->assertCount($fileExtensionCount, $mediaType->fileExtensions());
        $this->assertEquals($fileExtension, $mediaType->fileExtension());
    }

    public static function fileExtensionsDataProvider(): Generator
    {
        yield [MediaType::IMAGE_JPEG, 2, FileExtension::JPG];
        yield [MediaType::IMAGE_JPG, 2, FileExtension::JPG];
        yield [MediaType::IMAGE_PJPEG, 2, FileExtension::JPG];
        yield [MediaType::IMAGE_X_JPEG, 2, FileExtension::JPG];
        yield [MediaType::IMAGE_WEBP, 1, FileExtension::WEBP];
        yield [MediaType::IMAGE_X_WEBP, 1, FileExtension::WEBP];
        yield [MediaType::IMAGE_GIF, 1, FileExtension::GIF];
        yield [MediaType::IMAGE_PNG, 1, FileExtension::PNG];
        yield [MediaType::IMAGE_X_PNG, 1, FileExtension::PNG];
        yield [MediaType::IMAGE_AVIF, 1, FileExtension::AVIF];
        yield [MediaType::IMAGE_X_AVIF, 1, FileExtension::AVIF];
        yield [MediaType::IMAGE_BMP, 1, FileExtension::BMP];
        yield [MediaType::IMAGE_MS_BMP, 1, FileExtension::BMP];
        yield [MediaType::IMAGE_X_BITMAP, 1, FileExtension::BMP];
        yield [MediaType::IMAGE_X_BMP, 1, FileExtension::BMP];
        yield [MediaType::IMAGE_X_MS_BMP, 1, FileExtension::BMP];
        yield [MediaType::IMAGE_X_WINDOWS_BMP, 1, FileExtension::BMP];
        yield [MediaType::IMAGE_X_WIN_BITMAP, 1, FileExtension::BMP];
        yield [MediaType::IMAGE_X_XBITMAP, 1, FileExtension::BMP];
        yield [MediaType::IMAGE_TIFF, 2, FileExtension::TIF];
        yield [MediaType::IMAGE_JP2, 8, FileExtension::JP2];
        yield [MediaType::IMAGE_JPX, 8, FileExtension::JP2];
        yield [MediaType::IMAGE_JPM, 8, FileExtension::JP2];
        yield [MediaType::IMAGE_HEIC, 2, FileExtension::HEIC];
        yield [MediaType::IMAGE_X_HEIC, 2, FileExtension::HEIC];
        yield [MediaType::IMAGE_HEIF, 2, FileExtension::HEIC];
    }
}
