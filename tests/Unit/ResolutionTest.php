<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Intervention\Image\Exceptions\InvalidArgumentException;
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

    public function testConstructorNegativeX(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Resolution(-1, 1);
    }

    public function testConstructorNegativeY(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Resolution(1, -1);
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

    public function testLength(): void
    {
        $resolution = new Resolution(1, 1);
        $this->assertEquals(Length::INCH, $resolution->length());

        $resolution = new Resolution(1, 1, Length::CM);
        $this->assertEquals(Length::CM, $resolution->length());
    }

    public function testDpiFactory(): void
    {
        $resolution = Resolution::dpi(300, 150);
        $this->assertInstanceOf(Resolution::class, $resolution);
        $this->assertEquals(300, $resolution->x());
        $this->assertEquals(150, $resolution->y());
        $this->assertEquals(Length::INCH, $resolution->length());
    }

    public function testPpiFactory(): void
    {
        $resolution = Resolution::ppi(300, 150);
        $this->assertInstanceOf(Resolution::class, $resolution);
        $this->assertEquals(300, $resolution->x());
        $this->assertEquals(150, $resolution->y());
        $this->assertEquals(Length::CM, $resolution->length());
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

    public function testPerCmWhenAlreadyCm(): void
    {
        $resolution = new Resolution(100, 200, Length::CM);
        $result = $resolution->perCm();
        $this->assertSame($resolution, $result);
        $this->assertEquals(100, $result->x());
        $this->assertEquals(200, $result->y());
        $this->assertEquals(Length::CM, $result->length());
    }

    public function testPerInchWhenAlreadyInch(): void
    {
        $resolution = new Resolution(300, 150, Length::INCH);
        $result = $resolution->perInch();
        $this->assertSame($resolution, $result);
        $this->assertEquals(300, $result->x());
        $this->assertEquals(150, $result->y());
        $this->assertEquals(Length::INCH, $result->length());
    }

    public function testToString(): void
    {
        $resolution = new Resolution(300, 150, Length::CM);
        $this->assertEquals('300.00 x 150.00 dpcm', $resolution->toString());

        $resolution = new Resolution(300, 150, Length::INCH);
        $this->assertEquals('300.00 x 150.00 dpi', $resolution->toString());
        $this->assertEquals('300.00 x 150.00 dpi', (string) $resolution);
    }

    public function testDpiStaticFactory(): void
    {
        $resolution = Resolution::dpi(300, 150);
        $this->assertInstanceOf(Resolution::class, $resolution);
        $this->assertEquals(300, $resolution->x());
        $this->assertEquals(150, $resolution->y());
        $this->assertEquals(Length::INCH, $resolution->length());
    }

    public function testPpiStaticFactory(): void
    {
        $resolution = Resolution::ppi(118, 59);
        $this->assertInstanceOf(Resolution::class, $resolution);
        $this->assertEquals(118, $resolution->x());
        $this->assertEquals(59, $resolution->y());
        $this->assertEquals(Length::CM, $resolution->length());
    }

    public function testNegativeXThrowsException(): void
    {
        $this->expectException(\Intervention\Image\Exceptions\InvalidArgumentException::class);
        new Resolution(-1, 100);
    }

    public function testNegativeYThrowsException(): void
    {
        $this->expectException(\Intervention\Image\Exceptions\InvalidArgumentException::class);
        new Resolution(100, -1);
    }

    public function testZeroValuesAllowed(): void
    {
        $resolution = new Resolution(0, 0);
        $this->assertEquals(0, $resolution->x());
        $this->assertEquals(0, $resolution->y());
    }
}
