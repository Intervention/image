<?php

declare(strict_types=1);

namespace Intervention\Image\Tests;

use Intervention\Image\Resolution;

/**
 * @covers \Intervention\Image\Resolution
 */
class ResolutionTest extends TestCase
{
    public function testConstructor()
    {
        $resolution = new Resolution(1, 2);
        $this->assertInstanceOf(Resolution::class, $resolution);
    }

    public function testXY(): void
    {
        $resolution = new Resolution(1.2, 3.4);
        $this->assertEquals(1.2, $resolution->x());
        $this->assertEquals(3.4, $resolution->y());
    }

    public function testPerInch(): void
    {
        $resolution = new Resolution(300, 150); // per inch
        $this->assertEquals(300, $resolution->perInch()->x());
        $this->assertEquals(150, $resolution->perInch()->y());

        $resolution = new Resolution(300, 150, Resolution::PER_CM);
        $this->assertEquals(118.11024, round($resolution->perInch()->x(), 5));
        $this->assertEquals(59.05512, round($resolution->perInch()->y(), 5));
    }

    public function testPerCm(): void
    {
        $resolution = new Resolution(118.11024, 59.05512); // per inch
        $this->assertEquals(300, round($resolution->perCm()->x()));
        $this->assertEquals(150, round($resolution->perCm()->y()));

        $resolution = new Resolution(300, 150, Resolution::PER_CM);
        $this->assertEquals(300, $resolution->perCm()->x());
        $this->assertEquals(150, $resolution->perCm()->y());
    }

    public function testToString(): void
    {
        $resolution = new Resolution(300, 150, Resolution::PER_CM);
        $this->assertEquals('300.00 x 150.00 dpcm', $resolution->toString());

        $resolution = new Resolution(300, 150, Resolution::PER_INCH);
        $this->assertEquals('300.00 x 150.00 dpi', $resolution->toString());
        $this->assertEquals('300.00 x 150.00 dpi', (string) $resolution);
    }
}
