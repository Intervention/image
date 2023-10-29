<?php

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
}
