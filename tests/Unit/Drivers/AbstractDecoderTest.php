<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers;

use PHPUnit\Framework\Attributes\CoversClass;
use Exception;
use Generator;
use Intervention\Image\Drivers\AbstractDecoder;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\CollectionInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Tests\BaseTestCase;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use stdClass;
use Throwable;

#[CoversClass(AbstractDecoder::class)]
final class AbstractDecoderTest extends BaseTestCase
{
    public function testIsGifFormat(): void
    {
        $decoder = Mockery::mock(AbstractDecoder::class);
        $this->assertFalse($decoder->isGifFormat($this->getTestResourceData('exif.jpg')));
        $this->assertTrue($decoder->isGifFormat($this->getTestResourceData('red.gif')));
    }

    public function testExtractExifDataFromBinary(): void
    {
        $source = $this->getTestResourceData('exif.jpg');
        $pointer = $this->getTestResourcePointer('exif.jpg');
        $decoder = Mockery::mock(AbstractDecoder::class);
        $decoder->shouldReceive('buildFilePointerOrFail')->with($source)->andReturn($pointer);
        $result = $decoder->extractExifData($source);
        $this->assertInstanceOf(CollectionInterface::class, $result);
        $this->assertEquals('Oliver Vogel', $result->get('IFD0.Artist'));
    }

    public function testExtractExifDataFromPath(): void
    {
        $decoder = Mockery::mock(AbstractDecoder::class);
        $result = $decoder->extractExifData($this->getTestResourcePath('exif.jpg'));
        $this->assertInstanceOf(CollectionInterface::class, $result);
        $this->assertEquals('Oliver Vogel', $result->get('IFD0.Artist'));
    }

    public function testIsValidBase64(): void
    {
        $decoder = new class () extends AbstractDecoder
        {
            public function isValid(mixed $input): bool
            {
                try {
                    parent::decodeBase64Data($input);
                } catch (Throwable) {
                    return false;
                }
                return true;
            }

            public function decode(mixed $input): ImageInterface|ColorInterface
            {
                throw new Exception('');
            }
        };

        $this->assertTrue(
            $decoder->isValid('R0lGODdhAwADAKIAAAQyrKTy/ByS7AQytLT2/AAAAAAAAAAAACwAAAAAAwADAAADBhgU0gMgAQA7')
        );
        $this->assertFalse(
            $decoder->isValid('foo')
        );

        $this->assertFalse(
            $decoder->isValid(new stdClass())
        );
    }

    #[DataProvider('pathDataProvider')]
    public function testResolveFilePath(bool $valid, string $path): void
    {
        $decoder = new class () extends AbstractDecoder
        {
            public function decode(mixed $input): ImageInterface|ColorInterface
            {
                throw new Exception('');
            }

            public function checkValidityResult(string $path, bool $result): bool
            {
                try {
                    $this->parseFilePath($path);
                } catch (DecoderException) {
                    return $result === false;
                }

                return $result === true;
            }
        };

        $this->assertTrue($decoder->checkValidityResult($path, $valid));
    }

    public static function pathDataProvider(): Generator
    {
        yield [true, self::getTestResourcePath()];
        yield [false, 'foo'];
        yield [false, 'foo/bar'];
    }
}
