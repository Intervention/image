<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers;

use Intervention\Image\Drivers\AbstractDecoder;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
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
}
