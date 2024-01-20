<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers;

use Intervention\Image\Drivers\AbstractDecoder;
use Intervention\Image\Drivers\AbstractInputHandler;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Tests\TestCase;
use Mockery;

/**
 * @covers \Intervention\Image\Drivers\AbstractInputHandler
 */
final class AbstractInputHandlerTest extends TestCase
{
    public function testHandle(): void
    {
        $image = Mockery::mock(ImageInterface::class);

        $chain = Mockery::mock(AbstractDecoder::class);
        $chain->shouldReceive('handle')->with('test image')->andReturn($image);
        $chain->shouldReceive('decode')->with('test image')->andReturn(Mockery::mock(ImageInterface::class));

        $modifier = $this->getModifier($chain);
        $modifier->handle('test image');
    }

    public function testChainNoItems(): void
    {
        $handler = new class () extends AbstractInputHandler
        {
        };

        $this->expectException(DecoderException::class);
        $handler->handle('test');
    }

    private function getModifier(AbstractDecoder $chain): AbstractInputHandler
    {
        return new class ([$chain]) extends AbstractInputHandler
        {
            public function __construct(protected array $decoders = [])
            {
                //
            }

            protected function chain(): AbstractDecoder
            {
                return $this->decoders[0];
            }
        };
    }
}
