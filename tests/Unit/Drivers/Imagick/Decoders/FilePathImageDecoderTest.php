<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Decoders;

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

    public static function validFormatPathsProvider(): array
    {
        return [
            [self::getTestResourcePath('cats.gif'), true],
            [self::getTestResourcePath('animation.gif'), true],
            [self::getTestResourcePath('red.gif'), true],
            [self::getTestResourcePath('green.gif'), true],
            [self::getTestResourcePath('blue.gif'), true],
            [self::getTestResourcePath('gradient.bmp'), true],
            [self::getTestResourcePath('circle.png'), true],
            ['no-path', false],
            [str_repeat('x', PHP_MAXPATHLEN + 1), false],
        ];
    }
}
