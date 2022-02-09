<?php

namespace Intervention\Image\Tests\Drivers\Abstract;

use Intervention\Image\Drivers\Abstract\AbstractColor;
use Intervention\Image\Tests\TestCase;
use Mockery;

/**
 * @covers \Intervention\Image\Drivers\Abstract\AbstractColor
 */
class AbstractColorTest extends TestCase
{
    public function testToHex(): void
    {
        $color = Mockery::mock(AbstractColor::class)->makePartial();
        $color->shouldReceive('red')->andReturn(255);
        $color->shouldReceive('green')->andReturn(0);
        $color->shouldReceive('blue')->andReturn(0);

        $this->assertEquals('ff0000', $color->toHex());
        $this->assertEquals('#ff0000', $color->toHex('#'));
    }

    public function testIsGreyscale(): void
    {
        $color = Mockery::mock(AbstractColor::class)->makePartial();
        $color->shouldReceive('red')->andReturn(255);
        $color->shouldReceive('green')->andReturn(0);
        $color->shouldReceive('blue')->andReturn(0);
        $this->assertFalse($color->isGreyscale());

        $color = Mockery::mock(AbstractColor::class)->makePartial();
        $color->shouldReceive('red')->andReturn(100);
        $color->shouldReceive('green')->andReturn(100);
        $color->shouldReceive('blue')->andReturn(100);
        $this->assertTrue($color->isGreyscale());
    }
}
