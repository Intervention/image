<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Intervention\Image\Format;
use Intervention\Image\MediaType;
use Intervention\Image\Tests\BaseTestCase;

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
        $this->assertEquals(Format::JPEG2000, $mime->format());

        $mime = MediaType::IMAGE_JPX;
        $this->assertEquals(Format::JPEG2000, $mime->format());

        $mime = MediaType::IMAGE_JP2;
        $this->assertEquals(Format::JPEG2000, $mime->format());
    }

    public function testFormatHeic(): void
    {
        $mime = MediaType::IMAGE_HEIC;
        $this->assertEquals(Format::HEIC, $mime->format());

        $mime = MediaType::IMAGE_HEIF;
        $this->assertEquals(Format::HEIC, $mime->format());
    }
}
