<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Intervention\Image\FileExtension;
use Intervention\Image\Format;
use Intervention\Image\Tests\BaseTestCase;

final class FileExtensionTest extends BaseTestCase
{
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
        $this->assertEquals(Format::JPEG2000, $ext->format());

        $ext = FileExtension::J2K;
        $this->assertEquals(Format::JPEG2000, $ext->format());

        $ext = FileExtension::J2C;
        $this->assertEquals(Format::JPEG2000, $ext->format());

        $ext = FileExtension::JPG2;
        $this->assertEquals(Format::JPEG2000, $ext->format());
    }

    public function testFormatHeic(): void
    {
        $ext = FileExtension::HEIC;
        $this->assertEquals(Format::HEIC, $ext->format());

        $ext = FileExtension::HEIF;
        $this->assertEquals(Format::HEIC, $ext->format());
    }
}
