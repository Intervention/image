<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers;

use Intervention\Image\Drivers\SpecializableDecoder;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(SpecializableDecoder::class)]
final class SpecializableDecoderTest extends BaseTestCase
{
    public function testDecode(): void
    {
        $decoder = new class () extends SpecializableDecoder {
            //
        };
        $this->expectException(DriverException::class);
        $decoder->decode(null);
    }

    public function testSupports(): void
    {
        $decoder = new class () extends SpecializableDecoder {
            //
        };
        $this->expectException(DriverException::class);
        $decoder->supports(null);
    }
}
