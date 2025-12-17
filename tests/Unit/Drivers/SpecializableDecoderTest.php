<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers;

use Intervention\Image\Drivers\SpecializableDecoder;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Tests\BaseTestCase;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(SpecializableDecoder::class)]
final class SpecializableDecoderTest extends BaseTestCase
{
    public function testDecode(): void
    {
        $decoder = Mockery::mock(SpecializableDecoder::class)->makePartial();
        $this->expectException(DriverException::class);
        $decoder->decode(null);
    }
}
