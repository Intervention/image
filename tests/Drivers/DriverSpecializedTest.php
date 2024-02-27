<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers;

use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Drivers\DriverSpecialized;
use Mockery;

/**
 *
 * @internal
 */
#[CoversClass(\Intervention\Image\Drivers\DriverSpecialized::class)]
class DriverSpecializedTest extends TestCase
{
    public function testBuildSpecialized(): void
    {
        $generic = new JpegEncoder(quality: 10);
        $driver = Mockery::mock(DriverInterface::class);
        $baseclass = new class () extends DriverSpecialized
        {
        };

        $specialized = forward_static_call(
            [$baseclass, 'buildSpecialized'],
            $generic,
            $driver
        );

        $this->assertInstanceOf(JpegEncoder::class, $specialized->generic());
        $this->assertInstanceOf(DriverInterface::class, $specialized->driver());
        $this->assertEquals(10, $specialized->quality);
    }
}
