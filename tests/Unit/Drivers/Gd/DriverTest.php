<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd;

use Generator;
use Intervention\Image\Colors\Rgb\Colorspace;
use Intervention\Image\Colors\Rgb\Decoders\HexColorDecoder;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\FileExtension;
use Intervention\Image\Format;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorProcessorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\MediaType;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class DriverTest extends BaseTestCase
{
    protected Driver $driver;

    protected function setUp(): void
    {
        $this->driver = new Driver();
    }

    public function testId(): void
    {
        $this->assertEquals('GD', $this->driver->id());
    }

    public function testCreateImage(): void
    {
        $image = $this->driver->createImage(3, 2);
        $this->assertInstanceOf(ImageInterface::class, $image);
        $this->assertEquals(3, $image->width());
        $this->assertEquals(2, $image->height());
    }

    public function testCreateAnimation(): void
    {
        $image = $this->driver->createAnimation(function ($animation) {
            $animation->add($this->getTestResourcePath('red.gif'), .25);
            $animation->add($this->getTestResourcePath('green.gif'), .25);
        })->setLoops(5);
        $this->assertInstanceOf(ImageInterface::class, $image);

        $this->assertEquals(16, $image->width());
        $this->assertEquals(16, $image->height());
        $this->assertEquals(5, $image->loops());
        $this->assertEquals(2, $image->count());
    }

    public function testHandleInputImage(): void
    {
        $result = $this->driver->handleInput($this->getTestResourcePath('test.jpg'));
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testHandleInputColor(): void
    {
        $result = $this->driver->handleInput('ffffff');
        $this->assertInstanceOf(ColorInterface::class, $result);
    }

    public function testHandleInputObjects(): void
    {
        $result = $this->driver->handleInput('ffffff', [
            new HexColorDecoder()
        ]);
        $this->assertInstanceOf(ColorInterface::class, $result);
    }

    public function testHandleInputClassnames(): void
    {
        $result = $this->driver->handleInput('ffffff', [
            HexColorDecoder::class
        ]);
        $this->assertInstanceOf(ColorInterface::class, $result);
    }

    public function testColorProcessor(): void
    {
        $result = $this->driver->colorProcessor(new Colorspace());
        $this->assertInstanceOf(ColorProcessorInterface::class, $result);
    }

    #[DataProvider('supportsDataProvider')]
    public function testSupports(bool $result, mixed $identifier): void
    {
        $this->assertEquals($result, $this->driver->supports($identifier));
    }

    public static function supportsDataProvider(): Generator
    {
        yield [true, Format::JPEG];
        yield [true, MediaType::IMAGE_JPEG];
        yield [true, MediaType::IMAGE_JPG];
        yield [true, FileExtension::JPG];
        yield [true, FileExtension::JPEG];
        yield [true, 'jpg'];
        yield [true, 'jpeg'];
        yield [true, 'image/jpg'];
        yield [true, 'image/jpeg'];

        yield [true, Format::WEBP];
        yield [true, MediaType::IMAGE_WEBP];
        yield [true, MediaType::IMAGE_X_WEBP];
        yield [true, FileExtension::WEBP];
        yield [true, 'webp'];
        yield [true, 'image/webp'];
        yield [true, 'image/x-webp'];

        yield [true, Format::GIF];
        yield [true, MediaType::IMAGE_GIF];
        yield [true, FileExtension::GIF];
        yield [true, 'gif'];
        yield [true, 'image/gif'];

        yield [true, Format::PNG];
        yield [true, MediaType::IMAGE_PNG];
        yield [true, MediaType::IMAGE_X_PNG];
        yield [true, FileExtension::PNG];
        yield [true, 'png'];
        yield [true, 'image/png'];
        yield [true, 'image/x-png'];

        yield [true, Format::AVIF];
        yield [true, MediaType::IMAGE_AVIF];
        yield [true, MediaType::IMAGE_X_AVIF];
        yield [true, FileExtension::AVIF];
        yield [true, 'avif'];
        yield [true, 'image/avif'];
        yield [true, 'image/x-avif'];

        yield [true, Format::BMP];
        yield [true, FileExtension::BMP];
        yield [true, MediaType::IMAGE_BMP];
        yield [true, MediaType::IMAGE_MS_BMP];
        yield [true, MediaType::IMAGE_X_BITMAP];
        yield [true, MediaType::IMAGE_X_BMP];
        yield [true, MediaType::IMAGE_X_MS_BMP];
        yield [true, MediaType::IMAGE_X_WINDOWS_BMP];
        yield [true, MediaType::IMAGE_X_WIN_BITMAP];
        yield [true, MediaType::IMAGE_X_XBITMAP];
        yield [true, 'bmp'];
        yield [true, 'image/bmp'];
        yield [true, 'image/ms-bmp'];
        yield [true, 'image/x-bitmap'];
        yield [true, 'image/x-bmp'];
        yield [true, 'image/x-ms-bmp'];
        yield [true, 'image/x-windows-bmp'];
        yield [true, 'image/x-win-bitmap'];
        yield [true, 'image/x-xbitmap'];

        yield [false, Format::TIFF];
        yield [false, MediaType::IMAGE_TIFF];
        yield [false, FileExtension::TIFF];
        yield [false, FileExtension::TIF];
        yield [false, 'tif'];
        yield [false, 'tiff'];
        yield [false, 'image/tiff'];

        yield [false, Format::JP2];
        yield [false, MediaType::IMAGE_JP2];
        yield [false, MediaType::IMAGE_JPX];
        yield [false, MediaType::IMAGE_JPM];
        yield [false, FileExtension::TIFF];
        yield [false, FileExtension::TIF];
        yield [false, FileExtension::JP2];
        yield [false, FileExtension::J2K];
        yield [false, FileExtension::JPF];
        yield [false, FileExtension::JPM];
        yield [false, FileExtension::JPG2];
        yield [false, FileExtension::J2C];
        yield [false, FileExtension::JPC];
        yield [false, FileExtension::JPX];
        yield [false, 'jp2'];
        yield [false, 'j2k'];
        yield [false, 'jpf'];
        yield [false, 'jpm'];
        yield [false, 'jpg2'];
        yield [false, 'j2c'];
        yield [false, 'jpc'];
        yield [false, 'jpx'];

        yield [false, Format::HEIC];
        yield [false, MediaType::IMAGE_HEIC];
        yield [false, MediaType::IMAGE_HEIF];
        yield [false, FileExtension::HEIC];
        yield [false, FileExtension::HEIF];
        yield [false, 'heic'];
        yield [false, 'heif'];
        yield [false, 'image/heic'];
        yield [false, 'image/heif'];

        yield [false, 'tga'];
        yield [false, 'image/tga'];
        yield [false, 'image/x-targa'];
        yield [false, 'foo'];
        yield [false, ''];
    }
}
