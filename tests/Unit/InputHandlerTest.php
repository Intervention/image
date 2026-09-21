<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Generator;
use Intervention\Image\Colors\Rgb\Decoders\HexColorDecoder;
use Intervention\Image\Decoders\FilePathImageDecoder;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\InputHandler;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Resource;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Stringable;

#[RequiresPhpExtension('gd')]
#[RequiresPhpExtension('imagick')]
#[CoversClass(InputHandler::class)]
class InputHandlerTest extends BaseTestCase
{
    /**
     * @param array<string|DecoderInterface> $decoders
     */
    #[DataProvider('handleProvider')]
    public function testHandleDefaultDecoders(
        string $driver,
        array $decoders,
        mixed $input,
        string $outputClassname,
    ): void {
        $handler = new InputHandler(decoders: $decoders, driver: new $driver());
        if ($outputClassname === ImageInterface::class || $outputClassname === ColorInterface::class) {
            $this->assertInstanceOf($outputClassname, $handler->handle($input));
        } else {
            $this->expectException($outputClassname);
            $handler->handle($input);
        }
    }

    public static function handleProvider(): Generator
    {
        $base = [
            [InputHandler::COLOR_DECODERS, null, InvalidArgumentException::class],
            [InputHandler::COLOR_DECODERS, '', InvalidArgumentException::class],
            [InputHandler::COLOR_DECODERS, 'fff', ColorInterface::class],
            [InputHandler::COLOR_DECODERS, 'rgba(0, 0, 0, 0)', ColorInterface::class],
            [InputHandler::COLOR_DECODERS, 'cmyk(0, 0, 0, 0)', ColorInterface::class],
            [InputHandler::COLOR_DECODERS, 'hsv(0, 0, 0)', ColorInterface::class],
            [InputHandler::COLOR_DECODERS, 'hsl(0, 0, 0)', ColorInterface::class],
            [InputHandler::COLOR_DECODERS, 'steelblue', ColorInterface::class],
            [InputHandler::IMAGE_DECODERS, Resource::create()->path(), ImageInterface::class],
            [InputHandler::IMAGE_DECODERS, Resource::create()->data(), ImageInterface::class],
        ];

        $drivers = [GdDriver::class, ImagickDriver::class];
        foreach ($drivers as $driver) {
            foreach ($base as $line) {
                array_unshift($line, $driver); // prepend driver
                yield $line;
            }
        }
    }

    /**
     * Decode real Unicode paths through the default decoder selection.
     */
    #[DataProvider('unicodeFilePathsProvider')]
    public function testHandleUnicodeFilePath(
        string $driver,
        string|Stringable $input,
    ): void {
        $handler = new InputHandler(InputHandler::IMAGE_DECODERS, new $driver());
        $image = $handler->handle($input);

        $this->assertInstanceOf(ImageInterface::class, $image);
        $this->assertSame(20, $image->width());
        $this->assertSame(30, $image->height());
        $this->assertSame(realpath((string) $input), $image->origin()->filePath());
    }

    /**
     * Cover both drivers, path forms and supported string representations.
     */
    public static function unicodeFilePathsProvider(): Generator
    {
        foreach (['фото.jpg', '图片.jpg'] as $filename) {
            $resource = Resource::create($filename);
            $relativePath = 'tests/resources/' . $filename;
            $relativeStringable = new class ($relativePath) implements Stringable {
                public function __construct(private string $path)
                {
                    //
                }

                public function __toString(): string
                {
                    return $this->path;
                }
            };

            $inputs = [$resource->path(), $resource->stringablePath(), $relativePath, $relativeStringable];

            foreach ([GdDriver::class, ImagickDriver::class] as $driver) {
                foreach ($inputs as $input) {
                    yield [$driver, $input];
                }
            }
        }
    }

    public function testResolveWithoutDriver(): void
    {
        $handler = new InputHandler([new HexColorDecoder()]);
        $result = $handler->handle('fff');
        $this->assertInstanceOf(ColorInterface::class, $result);

        $handler = new InputHandler([HexColorDecoder::class]);
        $result = $handler->handle('fff');
        $this->assertInstanceOf(ColorInterface::class, $result);
    }

    public function testUsingDecodersStaticFactory(): void
    {
        $handler = InputHandler::usingDecoders([HexColorDecoder::class]);
        $result = $handler->handle('fff');
        $this->assertInstanceOf(ColorInterface::class, $result);
    }

    public function testUsingDecodersStaticFactoryWithDriver(): void
    {
        $handler = InputHandler::usingDecoders([HexColorDecoder::class], new GdDriver());
        $result = $handler->handle('fff');
        $this->assertInstanceOf(ColorInterface::class, $result);
    }

    public function testInvalidDecoderClass(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $handler = new InputHandler([self::class]);
        $handler->handle('fff');
    }

    public function testHandleNull(): void
    {
        $handler = new InputHandler([HexColorDecoder::class]);
        $this->expectException(InvalidArgumentException::class);
        $handler->handle(null);
    }

    public function testHandleEmptyString(): void
    {
        $handler = new InputHandler([HexColorDecoder::class]);
        $this->expectException(InvalidArgumentException::class);
        $handler->handle('');
    }

    public function testHandleUnsupportedInput(): void
    {
        $handler = new InputHandler(InputHandler::COLOR_DECODERS, new GdDriver());
        $this->expectException(\Intervention\Image\Exceptions\NotSupportedException::class);
        $handler->handle(new \stdClass());
    }

    public function testSpecializeDecoderThrowsDriverException(): void
    {
        $driver = Mockery::mock(DriverInterface::class);
        $driver->shouldReceive('specializeDecoder')
            ->andThrow(new NotSupportedException('Not supported'));

        $handler = new InputHandler([FilePathImageDecoder::class], $driver);
        $this->expectException(DriverException::class);
        $handler->handle('/some/path');
    }
}
