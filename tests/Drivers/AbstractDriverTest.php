<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers;

use Intervention\Image\Drivers\AbstractDriver;
use Intervention\Image\Encoders\PngEncoder as GenericEncoder;
use Intervention\Image\Drivers\Encoders\PngEncoder as SpecializedEncoder;
use Intervention\Image\Tests\TestCase;
use Mockery;

/**
 * @covers \Intervention\Image\Drivers\AbstractDriver
 *
 * @internal
 */
class AbstractDriverTest extends TestCase
{
    public function testResolve(): void
    {
        $driver = Mockery::mock(AbstractDriver::class)->makePartial();
        $encoder = new GenericEncoder();
        $this->assertInstanceOf(GenericEncoder::class, $encoder);
        $result = $driver->resolve(new GenericEncoder());
        $this->assertInstanceOf(SpecializedEncoder::class, $result);
    }
}
