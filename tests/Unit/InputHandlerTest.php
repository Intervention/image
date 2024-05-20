<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Intervention\Image\Colors\Rgb\Decoders\HexColorDecoder;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\InputHandler;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('gd')]
#[RequiresPhpExtension('imagick')]
#[CoversClass(InputHandler::class)]
class InputHandlerTest extends BaseTestCase
{
    #[DataProvider('testHandleProvider')]
    public function testHandleDefaultDecoders(string $driver, mixed $input, string $outputClassname): void
    {
        $handler = new InputHandler(driver: new $driver());
        if ($outputClassname === ImageInterface::class || $outputClassname === ColorInterface::class) {
            $this->assertInstanceOf($outputClassname, $handler->handle($input));
        } else {
            $this->expectException($outputClassname);
            $handler->handle($input);
        }
    }

    public static function testHandleProvider(): array
    {
        $base = [
            [null, DecoderException::class],
            ['', DecoderException::class],
            ['fff', ColorInterface::class],
            ['rgba(0, 0, 0, 0)', ColorInterface::class],
            ['cmyk(0, 0, 0, 0)', ColorInterface::class],
            ['hsv(0, 0, 0)', ColorInterface::class],
            ['hsl(0, 0, 0)', ColorInterface::class],
            ['transparent', ColorInterface::class],
            ['steelblue', ColorInterface::class],
            [self::getTestResourcePath(), ImageInterface::class],
            [file_get_contents(self::getTestResourcePath()), ImageInterface::class],
        ];

        $data = [];
        $drivers = [GdDriver::class, ImagickDriver::class];
        foreach ($drivers as $driver) {
            foreach ($base as $line) {
                array_unshift($line, $driver); // prepend driver
                $data[] = $line;
            }
        }

        return $data;
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
