<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Intervention\Image\Length;
use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Resolution;
use Intervention\Image\Tests\BaseTestCase;

#[CoversClass(Resolution::class)]
final class ResolutionTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $resolution = new Resolution(1, 2);
        $this->assertInstanceOf(Resolution::class, $resolution);
    }

    public function testIteration(): void
    {
        $resolution = new Resolution(1.2, 3.4);
        foreach ($resolution as $value) {
            $this->assertIsFloat($value);
        }
    }

    public function testXY(): void
    {
        $resolution = new Resolution(1.2, 3.4);
        $this->assertEquals(1.2, $resolution->x());
        $this->assertEquals(3.4, $resolution->y());
    }

    public function testUnit(): void
    {
        $resolution = new Resolution(1, 1);
        $this->assertEquals(Length::INCH, $resolution->unit());

        $resolution = new Resolution(1, 1, Length::CM);
        $this->assertEquals(Length::CM, $resolution->unit());
    }

    public function testConversion(): void
    {
        $resolution = new Resolution(300, 150); // per inch
        $this->assertEquals(300, $resolution->perInch()->x());
        $this->assertEquals(150, $resolution->perInch()->y());

        $resolution = new Resolution(300, 150); // per inch
        $this->assertEquals(118.11, round($resolution->perCm()->x(), 2));
        $this->assertEquals(59.06, round($resolution->perCm()->y(), 2));

        $resolution = new Resolution(118.11024, 59.06, Length::CM); // per cm
        $this->assertEquals(300, round($resolution->perInch()->x()));
        $this->assertEquals(150, round($resolution->perInch()->y()));
    }

    public function testToString(): void
    {
        $resolution = new Resolution(300, 150, Length::CM);
        $this->assertEquals('300.00 x 150.00 dpcm', $resolution->toString());

        $resolution = new Resolution(300, 150, Length::INCH);
        $this->assertEquals('300.00 x 150.00 dpi', $resolution->toString());
        $this->assertEquals('300.00 x 150.00 dpi', (string) $resolution);
    }
}
