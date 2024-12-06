<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers;

use PHPUnit\Framework\Attributes\CoversClass;
use Exception;
use Intervention\Image\Drivers\AbstractDecoder;
use Intervention\Image\Interfaces\CollectionInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Tests\BaseTestCase;
use Mockery;
use stdClass;

#[CoversClass(AbstractDecoder::class)]
final class AbstractDecoderTest extends BaseTestCase
{
    public function testIsGifFormat(): void
    {
        $decoder = Mockery::mock(AbstractDecoder::class);
        $this->assertFalse($decoder->isGifFormat($this->getTestResourceData('exif.jpg')));
        $this->assertTrue($decoder->isGifFormat($this->getTestResourceData('red.gif')));
    }

    public function testIsFile(): void
    {
        $decoder = Mockery::mock(AbstractDecoder::class);
        $this->assertTrue($decoder->isFile($this->getTestResourcePath()));
        $this->assertFalse($decoder->isFile('non-existent-file'));
        $this->assertFalse($decoder->isFile(new stdClass()));
        $this->assertFalse($decoder->isFile(str_repeat('o', PHP_MAXPATHLEN + 1)));
        $this->assertFalse($decoder->isFile(__DIR__));
    }

    public function testExtractExifDataFromBinary(): void
    {
        $source = $this->getTestResourceData('exif.jpg');
        $pointer = $this->getTestResourcePointer('exif.jpg');
        $decoder = Mockery::mock(AbstractDecoder::class);
        $decoder->shouldReceive('buildFilePointer')->with($source)->andReturn($pointer);
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

    public function testParseDataUri(): void
    {
        $decoder = new class () extends AbstractDecoder
        {
            public function parse(mixed $input): object
            {
                return parent::parseDataUri($input);
            }

            public function decode(mixed $input): ImageInterface|ColorInterface
            {
                throw new Exception('');
            }
        };

        $result = $decoder->parse(
            'data:image/gif;foo=bar;base64,R0lGODdhAwADAKIAAAQyrKTy/ByS7AQytLT2/AAAAAAAAAAAACwAAAAAAwADAAADBhgU0gMgAQA7'
        );

        $this->assertTrue($result->isValid());
        $this->assertEquals('image/gif', $result->mediaType());
        $this->assertTrue($result->hasMediaType());
        $this->assertTrue($result->isBase64Encoded());
        $this->assertEquals(
            'R0lGODdhAwADAKIAAAQyrKTy/ByS7AQytLT2/AAAAAAAAAAAACwAAAAAAwADAAADBhgU0gMgAQA7',
            $result->data()
        );

        $result = $decoder->parse('data:text/plain;charset=utf-8,test');
        $this->assertTrue($result->isValid());
        $this->assertEquals('text/plain', $result->mediaType());
        $this->assertTrue($result->hasMediaType());
        $this->assertFalse($result->isBase64Encoded());
        $this->assertEquals('test', $result->data());

        $result = $decoder->parse('data:;charset=utf-8,');
        $this->assertTrue($result->isValid());
        $this->assertNull($result->mediaType());
        $this->assertFalse($result->hasMediaType());
        $this->assertFalse($result->isBase64Encoded());
        $this->assertNull($result->data());
    }

    public function testIsValidBase64(): void
    {
        $decoder = new class () extends AbstractDecoder
        {
            public function isValid(mixed $input): bool
            {
                return parent::isValidBase64($input);
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
}
