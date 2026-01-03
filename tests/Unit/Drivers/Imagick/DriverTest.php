<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick;

use Generator;
use Intervention\Image\Analyzers\WidthAnalyzer as GenericWidthAnalyzer;
use Intervention\Image\Decoders\FilePathImageDecoder as GenericFilePathImageDecoder;
use Intervention\Image\Encoders\PngEncoder as GenericPngEncoder;
use Intervention\Image\Modifiers\ResizeModifier as GenericResizeModifier;
use Intervention\Image\Colors\Rgb\Colorspace;
use Intervention\Image\Drivers\Imagick\Analyzers\WidthAnalyzer;
use Intervention\Image\Drivers\Imagick\Decoders\FilePathImageDecoder;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Drivers\Imagick\Encoders\PngEncoder;
use Intervention\Image\Drivers\Imagick\Modifiers\ResizeModifier;
use Intervention\Image\Exceptions\ImageException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\FileExtension;
use Intervention\Image\Format;
use Intervention\Image\Interfaces\AnalyzerInterface;
use Intervention\Image\Interfaces\ColorProcessorInterface;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializableInterface;
use Intervention\Image\MediaType;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Providers\InputDataProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('imagick')]
#[CoversClass(Driver::class)]
final class DriverTest extends BaseTestCase
{
    protected Driver $driver;

    protected function setUp(): void
    {
        $this->driver = new Driver();
    }

    public function testId(): void
    {
        $this->assertEquals('Imagick', $this->driver->id());
    }

    public function testCreateImage(): void
    {
        $image = $this->driver->createImage(3, 2);
        $this->assertInstanceOf(ImageInterface::class, $image);
        $this->assertEquals(3, $image->width());
        $this->assertEquals(2, $image->height());
    }

    /**
     * @param array<string|DecoderInterface> $decoders
     */
    #[DataProviderExternal(InputDataProvider::class, 'handleImageInputDataProvider')]
    public function testHandleImageInput(mixed $input, ?array $decoders, string $resultClassname): void
    {
        $this->assertInstanceOf($resultClassname, $this->driver->handleImageInput($input, $decoders));
    }

    /**
     * @param array<string|DecoderInterface> $decoders
     */
    #[DataProviderExternal(InputDataProvider::class, 'handleColorInputDataProvider')]
    public function testHandleColorInput(mixed $input, ?array $decoders, string $resultClassname): void
    {
        $this->assertInstanceOf($resultClassname, $this->driver->handleColorInput($input, $decoders));
    }

    /**
     * @param array<string|DecoderInterface> $decoders
     */
    #[DataProviderExternal(InputDataProvider::class, 'handleImageInputDataProvider')]
    public function testHandleColorInputFail(mixed $input, ?array $decoders, string $resultClassname): void
    {
        $this->expectException(ImageException::class);
        $this->driver->handleColorInput($input);
    }

    /**
     * @param array<string|DecoderInterface> $decoders
     */
    #[DataProviderExternal(InputDataProvider::class, 'handleColorInputDataProvider')]
    public function testHandleImageInputFail(mixed $input, ?array $decoders, string $resultClassname): void
    {
        $this->expectException(ImageException::class);
        $this->driver->handleImageInput($input);
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

        yield [true, Format::TIFF];
        yield [true, MediaType::IMAGE_TIFF];
        yield [true, FileExtension::TIFF];
        yield [true, FileExtension::TIF];
        yield [true, 'tif'];
        yield [true, 'tiff'];
        yield [true, 'image/tiff'];

        yield [true, Format::JP2];
        yield [true, MediaType::IMAGE_JP2];
        yield [true, MediaType::IMAGE_JPX];
        yield [true, MediaType::IMAGE_JPM];
        yield [true, FileExtension::TIFF];
        yield [true, FileExtension::TIF];
        yield [true, FileExtension::JP2];
        yield [true, FileExtension::J2K];
        yield [true, FileExtension::JPF];
        yield [true, FileExtension::JPM];
        yield [true, FileExtension::JPG2];
        yield [true, FileExtension::J2C];
        yield [true, FileExtension::JPC];
        yield [true, FileExtension::JPX];
        yield [true, 'jp2'];
        yield [true, 'j2k'];
        yield [true, 'jpf'];
        yield [true, 'jpm'];
        yield [true, 'jpg2'];
        yield [true, 'j2c'];
        yield [true, 'jpc'];
        yield [true, 'jpx'];

        yield [true, Format::HEIC];
        yield [true, MediaType::IMAGE_HEIC];
        yield [true, MediaType::IMAGE_HEIF];
        yield [true, FileExtension::HEIC];
        yield [true, FileExtension::HEIF];
        yield [true, 'heic'];
        yield [true, 'heif'];
        yield [true, 'image/heic'];
        yield [true, 'image/heif'];

        yield [false, 'tga'];
        yield [false, 'image/tga'];
        yield [false, 'image/x-targa'];
        yield [false, 'foo'];
        yield [false, ''];
    }

    public function testVersion(): void
    {
        $this->assertTrue(is_string($this->driver->version()));
    }

    public function testSpecializeModifier(): void
    {
        $this->assertInstanceOf(
            ResizeModifier::class,
            $this->driver->specializeModifier(new GenericResizeModifier()),
        );

        $this->assertInstanceOf(
            ResizeModifier::class,
            $this->driver->specializeModifier(new ResizeModifier()),
        );
    }

    public function testSpecializeAnalyzer(): void
    {
        $this->assertInstanceOf(
            WidthAnalyzer::class,
            $this->driver->specializeAnalyzer(new GenericWidthAnalyzer()),
        );

        $this->assertInstanceOf(
            WidthAnalyzer::class,
            $this->driver->specializeAnalyzer(new WidthAnalyzer()),
        );
    }

    public function testSpecializeEncoder(): void
    {
        $this->assertInstanceOf(
            PngEncoder::class,
            $this->driver->specializeEncoder(new GenericPngEncoder()),
        );

        $this->assertInstanceOf(
            PngEncoder::class,
            $this->driver->specializeEncoder(new PngEncoder()),
        );
    }

    public function testSpecializeDecoder(): void
    {
        $this->assertInstanceOf(
            FilePathImageDecoder::class,
            $this->driver->specializeDecoder(new GenericFilePathImageDecoder()),
        );

        $this->assertInstanceOf(
            FilePathImageDecoder::class,
            $this->driver->specializeDecoder(new FilePathImageDecoder()),
        );
    }

    public function testSpecializeFailure(): void
    {
        $this->expectException(NotSupportedException::class);
        $this->driver->specializeAnalyzer(new class () implements AnalyzerInterface, SpecializableInterface
        {
            protected DriverInterface $driver;

            public function analyze(ImageInterface $image): mixed
            {
                return true;
            }

            /** @return array<string, mixed> **/
            public function specializable(): array
            {
                return [];
            }

            public function setDriver(DriverInterface $driver): SpecializableInterface
            {
                return $this;
            }

            public function driver(): DriverInterface
            {
                return $this->driver;
            }
        });
    }
}
