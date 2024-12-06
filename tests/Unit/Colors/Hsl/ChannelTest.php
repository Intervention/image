<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Hsl;

use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\Hsl\Channels\Hue;
use Intervention\Image\Colors\Hsl\Channels\Saturation;
use Intervention\Image\Colors\Hsl\Channels\Luminance;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Tests\BaseTestCase;

#[CoversClass(Hue::class)]
#[CoversClass(Saturation::class)]
#[CoversClass(Luminance::class)]
final class ChannelTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $channel = new Hue(0);
        $this->assertInstanceOf(Hue::class, $channel);

        $channel = new Hue(value: 0);
        $this->assertInstanceOf(Hue::class, $channel);

        $channel = new Hue(normalized: 0);
        $this->assertInstanceOf(Hue::class, $channel);

        $this->expectException(ColorException::class);
        new Hue();

        $this->expectException(ColorException::class);
        new Hue(normalized: 2);
    }

    public function testConstructorFail(): void
    {
        $this->expectException(ColorException::class);
        new Hue(400);
    }

    public function testToInt(): void
    {
        $channel = new Hue(10);
        $this->assertEquals(10, $channel->toInt());
    }

    public function testToString(): void
    {
        $channel = new Hue(10);
        $this->assertEquals("10", $channel->toString());
        $this->assertEquals("10", (string) $channel);
    }

    public function testValue(): void
    {
        $channel = new Hue(10);
        $this->assertEquals(10, $channel->value());
    }

    public function testNormalize(): void
    {
        $channel = new Hue(360);
        $this->assertEquals(1, $channel->normalize());
        $channel = new Hue(180);
        $this->assertEquals(0.5, $channel->normalize());
        $channel = new Hue(0);
        $this->assertEquals(0, $channel->normalize());
        $channel = new Luminance(90);
        $this->assertEquals(.9, $channel->normalize());
    }

    public function testValidate(): void
    {
        $this->expectException(ColorException::class);
        new Hue(361);

        $this->expectException(ColorException::class);
        new Hue(-1);

        $this->expectException(ColorException::class);
        new Saturation(101);

        $this->expectException(ColorException::class);
        new Saturation(-1);

        $this->expectException(ColorException::class);
        new Luminance(101);

        $this->expectException(ColorException::class);
        new Luminance(-1);
    }
}
