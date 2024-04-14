<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd;

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

    public static function supportsDataProvider(): array
    {
        return [
            [true, Format::JPEG],
            [true, MediaType::IMAGE_JPEG],
            [true, MediaType::IMAGE_JPG],
            [true, FileExtension::JPG],
            [true, FileExtension::JPEG],
            [true, 'jpg'],
            [true, 'jpeg'],
            [true, 'image/jpg'],
            [true, 'image/jpeg'],

            [true, Format::WEBP],
            [true, MediaType::IMAGE_WEBP],
            [true, MediaType::IMAGE_X_WEBP],
            [true, FileExtension::WEBP],
            [true, 'webp'],
            [true, 'image/webp'],
            [true, 'image/x-webp'],

            [true, Format::GIF],
            [true, MediaType::IMAGE_GIF],
            [true, FileExtension::GIF],
            [true, 'gif'],
            [true, 'image/gif'],

            [true, Format::PNG],
            [true, MediaType::IMAGE_PNG],
            [true, MediaType::IMAGE_X_PNG],
            [true, FileExtension::PNG],
            [true, 'png'],
            [true, 'image/png'],
            [true, 'image/x-png'],

            [true, Format::AVIF],
            [true, MediaType::IMAGE_AVIF],
            [true, MediaType::IMAGE_X_AVIF],
            [true, FileExtension::AVIF],
            [true, 'avif'],
            [true, 'image/avif'],
            [true, 'image/x-avif'],

            [true, Format::BMP],
            [true, FileExtension::BMP],
            [true, MediaType::IMAGE_BMP],
            [true, MediaType::IMAGE_MS_BMP],
            [true, MediaType::IMAGE_X_BITMAP],
            [true, MediaType::IMAGE_X_BMP],
            [true, MediaType::IMAGE_X_MS_BMP],
            [true, MediaType::IMAGE_X_WINDOWS_BMP],
            [true, MediaType::IMAGE_X_WIN_BITMAP],
            [true, MediaType::IMAGE_X_XBITMAP],
            [true, 'bmp'],
            [true, 'image/bmp'],
            [true, 'image/ms-bmp'],
            [true, 'image/x-bitmap'],
            [true, 'image/x-bmp'],
            [true, 'image/x-ms-bmp'],
            [true, 'image/x-windows-bmp'],
            [true, 'image/x-win-bitmap'],
            [true, 'image/x-xbitmap'],

            [false, Format::TIFF],
            [false, MediaType::IMAGE_TIFF],
            [false, FileExtension::TIFF],
            [false, FileExtension::TIF],
            [false, 'tif'],
            [false, 'tiff'],
            [false, 'image/tiff'],

            [false, Format::JP2],
            [false, MediaType::IMAGE_JP2],
            [false, MediaType::IMAGE_JPX],
            [false, MediaType::IMAGE_JPM],
            [false, FileExtension::TIFF],
            [false, FileExtension::TIF],
            [false, FileExtension::JP2],
            [false, FileExtension::J2K],
            [false, FileExtension::JPF],
            [false, FileExtension::JPM],
            [false, FileExtension::JPG2],
            [false, FileExtension::J2C],
            [false, FileExtension::JPC],
            [false, FileExtension::JPX],
            [false, 'jp2'],
            [false, 'j2k'],
            [false, 'jpf'],
            [false, 'jpm'],
            [false, 'jpg2'],
            [false, 'j2c'],
            [false, 'jpc'],
            [false, 'jpx'],

            [false, Format::HEIC],
            [false, MediaType::IMAGE_HEIC],
            [false, MediaType::IMAGE_HEIF],
            [false, FileExtension::HEIC],
            [false, FileExtension::HEIF],
            [false, 'heic'],
            [false, 'heif'],
            [false, 'image/heic'],
            [false, 'image/heif'],

            [false, 'tga'],
            [false, 'image/tga'],
            [false, 'image/x-targa'],
            [false, 'foo'],
            [false, ''],
        ];
    }
}
