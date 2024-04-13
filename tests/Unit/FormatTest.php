<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

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
}
