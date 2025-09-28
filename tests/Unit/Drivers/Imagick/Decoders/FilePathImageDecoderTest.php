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
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\DataProvider;
use Stringable;

#[RequiresPhpExtension('imagick')]
#[CoversClass(FilePathImageDecoder::class)]
final class FilePathImageDecoderTest extends BaseTestCase
{
    protected FilePathImageDecoder $decoder;

    protected function setUp(): void
    {
        $this->decoder = new FilePathImageDecoder();
        $this->decoder->setDriver(new Driver());
    }

    #[DataProvider('validFormatPathsProvider')]
    public function testDecode(string|Stringable $path, bool $result): void
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
        yield [Resource::create('cats.gif')->path(), true];
        yield [Resource::create('animation.gif')->path(), true];
        yield [Resource::create('red.gif')->path(), true];
        yield [Resource::create('green.gif')->path(), true];
        yield [Resource::create('blue.gif')->path(), true];
        yield [Resource::create('gradient.bmp')->path(), true];
        yield [Resource::create('circle.png')->path(), true];
        yield [Resource::create('circle.png')->stringablePath(), true];
        yield ['no-path', false];
        yield [str_repeat('x', PHP_MAXPATHLEN + 1), false];
    }
}
