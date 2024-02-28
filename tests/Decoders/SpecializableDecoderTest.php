<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Decoders;

use Intervention\Image\Decoders\SpecializableDecoder;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Tests\BaseTestCase;
use Mockery;

final class SpecializableDecoderTest extends BaseTestCase
{
    public function testDecode(): void
    {
        $decoder = Mockery::mock(SpecializableDecoder::class)->makePartial();
        $this->expectException(DecoderException::class);
        $decoder->decode(null);
    }
}
