<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Generator;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\FileExtension;
use Intervention\Image\Format;
use Intervention\Image\MediaType;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

#[CoversClass(FileExtension::class)]
final class FileExtensionTest extends BaseTestCase
{
    public function testCreate(): void
    {
        $this->assertEquals(FileExtension::JPG, FileExtension::create(MediaType::IMAGE_JPEG));
        $this->assertEquals(FileExtension::JPG, FileExtension::create(Format::JPEG));
        $this->assertEquals(FileExtension::JPG, FileExtension::create(FileExtension::JPG));
        $this->assertEquals(FileExtension::JPG, FileExtension::create('jpg'));
        $this->assertEquals(FileExtension::JPEG, FileExtension::create('jpeg'));
        $this->assertEquals(FileExtension::JPG, FileExtension::create('image/jpeg'));
        $this->assertEquals(FileExtension::JPG, FileExtension::create('JPG'));
        $this->assertEquals(FileExtension::JPEG, FileExtension::create('JPEG'));
        $this->assertEquals(FileExtension::JPG, FileExtension::create('IMAGE/JPEG'));
    }

    public function testCreateUnknown(): void
    {
        $this->expectException(InvalidArgumentException::class);
        FileExtension::create('foo');
    }

    public function testTryCreate(): void
    {
        $this->assertEquals(FileExtension::JPG, FileExtension::tryCreate(MediaType::IMAGE_JPEG));
        $this->assertEquals(FileExtension::JPG, FileExtension::tryCreate(Format::JPEG));
        $this->assertEquals(FileExtension::JPG, FileExtension::tryCreate(FileExtension::JPG));
        $this->assertEquals(FileExtension::JPG, FileExtension::tryCreate('jpg'));
        $this->assertEquals(FileExtension::JPEG, FileExtension::tryCreate('jpeg'));
        $this->assertEquals(FileExtension::JPG, FileExtension::tryCreate('image/jpeg'));
        $this->assertNull(FileExtension::tryCreate('no-format'));
    }

    public function testFormatJpeg(): void
    {
        $ext = FileExtension::JPEG;
        $this->assertEquals(Format::JPEG, $ext->format());

        $ext = FileExtension::JPG;
        $this->assertEquals(Format::JPEG, $ext->format());
    }

    public function testFormatWebp(): void
    {
        $ext = FileExtension::WEBP;
        $this->assertEquals(Format::WEBP, $ext->format());
    }

    public function testFormatGif(): void
    {
        $ext = FileExtension::GIF;
        $this->assertEquals(Format::GIF, $ext->format());
    }

    public function testFormatPng(): void
    {
        $ext = FileExtension::PNG;
        $this->assertEquals(Format::PNG, $ext->format());
    }

    public function testFormatAvif(): void
    {
        $ext = FileExtension::AVIF;
        $this->assertEquals(Format::AVIF, $ext->format());
    }

    public function testFormatBmp(): void
    {
        $ext = FileExtension::BMP;
        $this->assertEquals(Format::BMP, $ext->format());
    }

    public function testFormatTiff(): void
    {
        $ext = FileExtension::TIFF;
        $this->assertEquals(Format::TIFF, $ext->format());

        $ext = FileExtension::TIF;
        $this->assertEquals(Format::TIFF, $ext->format());
    }

    public function testFormatJpeg2000(): void
    {
        $ext = FileExtension::JP2;
        $this->assertEquals(Format::JP2, $ext->format());

        $ext = FileExtension::J2K;
        $this->assertEquals(Format::JP2, $ext->format());

        $ext = FileExtension::J2C;
        $this->assertEquals(Format::JP2, $ext->format());

        $ext = FileExtension::JPG2;
        $this->assertEquals(Format::JP2, $ext->format());

        $ext = FileExtension::JP2K;
        $this->assertEquals(Format::JP2, $ext->format());

        $ext = FileExtension::JPF;
        $this->assertEquals(Format::JP2, $ext->format());

        $ext = FileExtension::JPX;
        $this->assertEquals(Format::JP2, $ext->format());

        $ext = FileExtension::JPC;
        $this->assertEquals(Format::JP2, $ext->format());

        $ext = FileExtension::JPM;
        $this->assertEquals(Format::JP2, $ext->format());
    }

    public function testFormatHeic(): void
    {
        $ext = FileExtension::HEIC;
        $this->assertEquals(Format::HEIC, $ext->format());

        $ext = FileExtension::HEIF;
        $this->assertEquals(Format::HEIC, $ext->format());
    }

    public function testFormatIco(): void
    {
        $ext = FileExtension::ICO;
        $this->assertEquals(Format::ICO, $ext->format());
    }

    public function testCreateFromMediaTypeString(): void
    {
        $this->assertEquals(FileExtension::JPG, FileExtension::create('image/jpeg'));
        $this->assertEquals(FileExtension::PNG, FileExtension::create('image/png'));
        $this->assertEquals(FileExtension::GIF, FileExtension::create('image/gif'));
        $this->assertEquals(FileExtension::BMP, FileExtension::create('image/bmp'));
        $this->assertEquals(FileExtension::WEBP, FileExtension::create('image/webp'));
    }

    #[DataProvider('mediaTypesDataProvider')]
    public function testMediatypes(FileExtension $extension, int $mediaTypeCount, MediaType $mediaType): void
    {
        $this->assertCount($mediaTypeCount, $extension->mediaTypes());
        $this->assertEquals($mediaType, $extension->mediaType());
    }

    public static function mediaTypesDataProvider(): Generator
    {
        yield [FileExtension::JPEG, 4, MediaType::IMAGE_JPEG];
        yield [FileExtension::WEBP, 2, MediaType::IMAGE_WEBP];
        yield [FileExtension::GIF, 1, MediaType::IMAGE_GIF];
        yield [FileExtension::PNG, 2, MediaType::IMAGE_PNG];
        yield [FileExtension::AVIF, 2, MediaType::IMAGE_AVIF];
        yield [FileExtension::BMP, 9, MediaType::IMAGE_BMP];
        yield [FileExtension::TIFF, 1, MediaType::IMAGE_TIFF];
        yield [FileExtension::TIF, 1, MediaType::IMAGE_TIFF];
        yield [FileExtension::JP2, 4, MediaType::IMAGE_JP2];
        yield [FileExtension::HEIC, 3, MediaType::IMAGE_HEIC];
        yield [FileExtension::HEIF, 3, MediaType::IMAGE_HEIC];
        yield [FileExtension::JPF, 4, MediaType::IMAGE_JP2];
        yield [FileExtension::JPX, 4, MediaType::IMAGE_JP2];
        yield [FileExtension::JPC, 4, MediaType::IMAGE_JP2];
        yield [FileExtension::JPM, 4, MediaType::IMAGE_JP2];
        yield [FileExtension::J2K, 4, MediaType::IMAGE_JP2];
        yield [FileExtension::J2C, 4, MediaType::IMAGE_JP2];
        yield [FileExtension::JP2K, 4, MediaType::IMAGE_JP2];
        yield [FileExtension::JPG2, 4, MediaType::IMAGE_JP2];
        yield [FileExtension::ICO, 2, MediaType::IMAGE_X_ICON];
        yield [FileExtension::JPG, 4, MediaType::IMAGE_JPEG];
    }
}
