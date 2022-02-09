<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Abstract\Decoders;

use Intervention\Image\Drivers\Abstract\Decoders\AbstractDecoder;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Tests\TestCase;
use Mockery;

/**
 * @covers \Intervention\Image\Drivers\Abstract\Decoders\AbstractDecoder
 */
class AbstractDecoderTest extends TestCase
{
    public function testHandle(): void
    {
        $decoder = Mockery::mock(AbstractDecoder::class)->makePartial();
        $decoder->shouldReceive('decode')->with('input string')->andReturn(null);

        $decoder->handle('input string');
    }

    public function testHandleFail(): void
    {
        $decoder = Mockery::mock(AbstractDecoder::class, [])->makePartial()->shouldAllowMockingProtectedMethods();
        $decoder->shouldReceive('decode')->with('input string')->andThrow(DecoderException::class);

        $this->expectException(DecoderException::class);
        $this->expectExceptionMessage('Unable to decode given input.');

        $decoder->handle('input string');
    }

    public function testHandleFailWithSuccessor(): void
    {
        $successor = Mockery::mock(AbstractDecoder::class)->makePartial();
        $successor->shouldReceive('decode')->with('input string')->andReturn(null);

        $decoder = Mockery::mock(AbstractDecoder::class, [$successor])->makePartial()->shouldAllowMockingProtectedMethods();
        $decoder->shouldReceive('decode')->with('input string')->andThrow(DecoderException::class);

        $decoder->handle('input string');
    }
}
