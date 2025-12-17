<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Decoders;

use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Drivers\Gd\Decoders\FilePathImageDecoder;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Exceptions\FileNotFoundException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Image;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\DataProvider;
use Stringable;

#[RequiresPhpExtension('gd')]
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
    public function testDecode(string|Stringable $path, ?string $exception): void
    {
        if ($exception !== null) {
            $this->expectException($exception);
        }

        $result = $this->decoder->decode($path);

        if ($exception === null) {
            $this->assertInstanceOf(Image::class, $result);
        }
    }

    public static function validFormatPathsProvider(): Generator
    {
        yield [Resource::create('cats.gif')->path(), null];
        yield [Resource::create('animation.gif')->path(), null];
        yield [Resource::create('red.gif')->path(), null];
        yield [Resource::create('green.gif')->path(), null];
        yield [Resource::create('blue.gif')->path(), null];
        yield [Resource::create('gradient.bmp')->path(), null];
        yield [Resource::create('circle.png')->path(), null];
        yield [Resource::create('circle.png')->stringablePath(), null];
        yield ['no-path', FileNotFoundException::class];
        yield [str_repeat('x', PHP_MAXPATHLEN + 1), InvalidArgumentException::class];
    }
}
