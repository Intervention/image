<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Generator;
use Intervention\Image\Colors\Rgb\Decoders\HexColorDecoder;
use Intervention\Image\Decoders\ColorDecoder;
use Intervention\Image\Decoders\ImageDecoder;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\InputHandler;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

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
            [[ColorDecoder::class], null, InvalidArgumentException::class],
            [[ColorDecoder::class], '', InvalidArgumentException::class],
            [[ColorDecoder::class], 'fff', ColorInterface::class],
            [[ColorDecoder::class], 'rgba(0, 0, 0, 0)', ColorInterface::class],
            [[ColorDecoder::class], 'cmyk(0, 0, 0, 0)', ColorInterface::class],
            [[ColorDecoder::class], 'hsv(0, 0, 0)', ColorInterface::class],
            [[ColorDecoder::class], 'hsl(0, 0, 0)', ColorInterface::class],
            [[ColorDecoder::class], 'transparent', ColorInterface::class],
            [[ColorDecoder::class], 'steelblue', ColorInterface::class],
            [[ImageDecoder::class], Resource::create()->path(), ImageInterface::class],
            [[ImageDecoder::class], Resource::create()->data(), ImageInterface::class],
        ];

        $drivers = [GdDriver::class, ImagickDriver::class];
        foreach ($drivers as $driver) {
            foreach ($base as $line) {
                array_unshift($line, $driver); // prepend driver
                yield $line;
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
}
