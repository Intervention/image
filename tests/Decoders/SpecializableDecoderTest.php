<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Decoders;

use Intervention\Image\Decoders\SpecializableDecoder;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Tests\TestCase;
use Mockery;

class SpecializableDecoderTest extends TestCase
{
    public function testDecode(): void
    {
        $decoder = Mockery::mock(SpecializableDecoder::class)->makePartial();
        $this->expectException(DecoderException::class);
        $decoder->decode(null);
    }
}
