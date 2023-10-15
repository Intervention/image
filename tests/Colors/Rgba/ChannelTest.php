<?php

namespace Intervention\Image\Tests\Colors\Rgba;

use Intervention\Image\Colors\Rgba\Channels\Red as Channel;
use Intervention\Image\Colors\Rgba\Channels\Alpha as Alpha;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Colors\Rgba\Channels\Red
 * @covers \Intervention\Image\Colors\Rgba\Channels\Green
 * @covers \Intervention\Image\Colors\Rgba\Channels\Blue
 * @covers \Intervention\Image\Colors\Rgba\Channels\Alpha
 */
class ChannelTest extends TestCase
{
    public function testConstructor(): void
    {
        $channel = new Channel(0);
        $this->assertInstanceOf(Channel::class, $channel);
    }

    public function testValue(): void
    {
        $channel = new Channel(10);
        $this->assertEquals(10, $channel->value());
    }

    public function testNormalize(): void
    {
        $channel = new Channel(255);
        $this->assertEquals(1, $channel->normalize());
        $channel = new Channel(0);
        $this->assertEquals(0, $channel->normalize());
        $channel = new Channel(51);
        $this->assertEquals(.2, $channel->normalize());
    }

    public function testValidate(): void
    {
        $this->expectException(ColorException::class);
        new Channel(256);

        $this->expectException(ColorException::class);
        new Channel(-1);
    }

    public function testToString(): void
    {
        $channel = new Channel(255);
        $this->assertEquals("255", $channel->toString());

        $channel = new Alpha(0);
        $this->assertEquals("0", $channel->toString());

        $channel = new Alpha(51);
        $this->assertEquals("0.2", $channel->toString());

        $channel = new Alpha(255);
        $this->assertEquals("1", $channel->toString());

        $channel = new Alpha(170);
        $this->assertEquals("0.666667", $channel->toString());
    }
}
