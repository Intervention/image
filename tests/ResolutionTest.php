<?php


namespace Intervention\Image;

use Intervention\Image\Exception\InvalidArgumentException;
use Intervention\Image\Exception\RuntimeException;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Intervention\Image\Resolution
 */
class ResolutionTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testConstructWithInvalidUnits()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Units \'invalid\' is not supported.');

        new Resolution(1, 2, 'invalid');
    }

    /**
     * @covers ::getX
     * @covers ::setX
     * @covers ::getY
     * @covers ::setY
     * @covers ::getUnits
     * @covers ::hasUnits
     * @covers ::setUnits
     * @covers ::convert
     */
    public function testGettersAndSetters()
    {
        // Defaults
        $x = 300;
        $y = 150;

        // Getters
        $resolution = new Resolution($x, $y, Resolution::UNITS_PPI);

        $this->assertEquals($x, $resolution->getX());
        $this->assertEquals($y, $resolution->getY());
        $this->assertEquals(Resolution::UNITS_PPI, $resolution->getUnits());
        $this->assertNotEquals(Resolution::UNITS_PPCM, $resolution->getUnits());
        $this->assertTrue($resolution->hasUnits(Resolution::UNITS_PPI));
        $this->assertFalse($resolution->hasUnits(Resolution::UNITS_PPCM));

        // ... with conversion
        $resolution = new Resolution($x, $y, Resolution::UNITS_PPI);

        $this->assertEquals($x, $resolution->getX($resolution->getUnits()));
        $this->assertEquals(Resolution::ppi2ppcm($x), $resolution->getX(Resolution::UNITS_PPCM));
        $this->assertEquals($y, $resolution->getY($resolution->getUnits()));
        $this->assertEquals(Resolution::ppi2ppcm($y), $resolution->getY(Resolution::UNITS_PPCM));

        // Setters
        $resolution = new Resolution($x, $y, Resolution::UNITS_PPI);
        $resolution->setX($y);
        $resolution->setY($x);

        $this->assertEquals($y, $resolution->getX());
        $this->assertEquals($x, $resolution->getY());

        // ... with conversion
        $resolution = new Resolution($x, $y, Resolution::UNITS_PPI);

        $resolution->setX($x, Resolution::UNITS_PPCM);
        $resolution->setY($y, Resolution::UNITS_PPCM);

        $this->assertEquals(Resolution::ppcm2ppi($x), $resolution->getX($resolution->getUnits()));
        $this->assertEquals($x, $resolution->getX(Resolution::UNITS_PPCM));
        $this->assertEquals(Resolution::ppcm2ppi($y), $resolution->getY($resolution->getUnits()));
        $this->assertEquals($y, $resolution->getY(Resolution::UNITS_PPCM));

        // Units Conversion
        $resolution = new Resolution($x, $y, Resolution::UNITS_PPCM);
        $resolution->setUnits(Resolution::UNITS_PPI);

        $this->assertEquals(Resolution::ppcm2ppi($x), $resolution->getX());
        $this->assertEquals(Resolution::ppcm2ppi($y), $resolution->getY());
        $this->assertEquals(Resolution::UNITS_PPI, $resolution->getUnits());
    }

    /**
     * @covers ::convert
     */
    public function testConvertUnknownToUnits()
    {
        // Unknown/Invalid units cannot be converted

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Impossible convert value from 'unknown' to 'ppi'.");

        (new Resolution(150, 250, Resolution::UNITS_UNKNOWN))->setUnits(Resolution::UNITS_PPI);
    }

    /**
     * @covers ::convert
     */
    public function testConvertUnitsToUnknown()
    {
        // Unknown/Invalid units cannot be converted

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Impossible convert value from 'ppi' to 'unknown'.");

        (new Resolution(150, 250, Resolution::UNITS_PPI))->setUnits(Resolution::UNITS_UNKNOWN);
    }

    /**
     * @covers ::toArray
     * @covers ::jsonSerialize
     */
    public function testToArrayJson()
    {
        $resolution = new Resolution(150, 250, Resolution::UNITS_PPI);
        $expected   = [
            'x'     => $resolution->getX(),
            'y'     => $resolution->getY(),
            'units' => $resolution->getUnits(),
        ];

        $this->assertEquals($expected, $resolution->toArray());
        $this->assertEquals($expected, $resolution->jsonSerialize());
    }

    /**
     * @covers ::ppcm2ppi
     */
    public function testPpcm2ppi()
    {
        $this->assertEquals((int)round(200 * 2.54), Resolution::ppcm2ppi(200));
    }

    /**
     * @covers ::ppi2ppcm
     */
    public function testPpi2ppcm()
    {
        $this->assertEquals((int)round(200 / 2.54), Resolution::ppi2ppcm(200));
    }
}
