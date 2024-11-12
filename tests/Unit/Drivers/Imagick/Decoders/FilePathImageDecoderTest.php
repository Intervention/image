<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Decoders;

use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Drivers\Imagick\Decoders\FilePathImageDecoder;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Image;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Decoders\FilePathImageDecoder::class)]
final class FilePathImageDecoderTest extends BaseTestCase
{
    protected FilePathImageDecoder $decoder;

    protected function setUp(): void
    {
        $this->decoder = new FilePathImageDecoder();
        $this->decoder->setDriver(new Driver());
    }

    #[DataProvider('validFormatPathsProvider')]
    public function testDecode(string $path, bool $result): void
    {
        if ($result === false) {
            $this->expectException(DecoderException::class);
        }

        $result = $this->decoder->decode($path);

        if ($result === true) {
            $this->assertInstanceOf(Image::class, $result);
        }
    }

    public static function validFormatPathsProvider(): Generator
    {
        yield [self::getTestResourcePath('cats.gif'), true];
        yield [self::getTestResourcePath('animation.gif'), true];
        yield [self::getTestResourcePath('red.gif'), true];
        yield [self::getTestResourcePath('green.gif'), true];
        yield [self::getTestResourcePath('blue.gif'), true];
        yield [self::getTestResourcePath('gradient.bmp'), true];
        yield [self::getTestResourcePath('circle.png'), true];
        yield ['no-path', false];
        yield [str_repeat('x', PHP_MAXPATHLEN + 1), false];
    }
}
