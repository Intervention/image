<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers;

use Exception;
use Intervention\Image\Drivers\AbstractDecoder;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Tests\TestCase;
use Mockery;

/**
 * @covers \Intervention\Image\Drivers\AbstractDecoder
 */
class AbstractDecoderTest extends TestCase
{
    public function testHandle(): void
    {
        $result = Mockery::mock(ColorInterface::class);
        $decoder = Mockery::mock(AbstractDecoder::class)->makePartial();
        $decoder->shouldReceive('decode')->with('test input')->andReturn($result);

        $decoder->handle('test input');
    }

    public function testHandleFail(): void
    {
        $decoder = Mockery::mock(AbstractDecoder::class, [])->makePartial()->shouldAllowMockingProtectedMethods();
        $decoder->shouldReceive('decode')->with('test input')->andThrow(DecoderException::class);

        $this->expectException(DecoderException::class);

        $decoder->handle('test input');
    }

    public function testHandleFailWithSuccessor(): void
    {
        $result = Mockery::mock(ColorInterface::class);
        $successor = Mockery::mock(AbstractDecoder::class)->makePartial();
        $successor->shouldReceive('decode')->with('test input')->andReturn($result);

        $decoder = Mockery::mock(
            AbstractDecoder::class,
            [$successor]
        )->makePartial()->shouldAllowMockingProtectedMethods();
        $decoder->shouldReceive('decode')->with('test input')->andThrow(DecoderException::class);

        $decoder->handle('test input');
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
            'data:image/gif;base64,R0lGODdhAwADAKIAAAQyrKTy/ByS7AQytLT2/AAAAAAAAAAAACwAAAAAAwADAAADBhgU0gMgAQA7'
        );

        $this->assertTrue($result->isValid());
        $this->assertEquals('image/gif', $result->mediaType());
        $this->assertTrue($result->hasMediaType());
        $this->assertTrue($result->isBase64Encoded());
        $this->assertEquals([], $result->parameters());
        $this->assertEquals(
            'R0lGODdhAwADAKIAAAQyrKTy/ByS7AQytLT2/AAAAAAAAAAAACwAAAAAAwADAAADBhgU0gMgAQA7',
            $result->data()
        );
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
    }
}
