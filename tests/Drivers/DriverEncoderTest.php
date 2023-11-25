<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers;

use Intervention\Image\Drivers\DriverEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Tests\TestCase;
use Mockery;

/**
 * @covers \Intervention\Image\Drivers\DriverEncoder
 *
 * @internal
 */
class DriverEncoderTest extends TestCase
{
    public function testGetBuffered(): void
    {
        $encoder = Mockery::mock(DriverEncoder::class)->makePartial();
        $result = $encoder->getBuffered(function () {
            echo 'result';
        });
        $this->assertEquals('result', $result);
    }

    public function testGetAttributes(): void
    {
        $encoder = Mockery::mock(DriverEncoder::class, [
            new PngEncoder(color_limit: 123),
            Mockery::mock(DriverInterface::class),
        ])->makePartial();

        $this->assertEquals(123, $encoder->color_limit);
    }
}
