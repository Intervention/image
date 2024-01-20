<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Colors\Rgb;

use Intervention\Image\Colors\Rgb\Channels\Red as Channel;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Colors\Rgb\Channels\Red
 * @covers \Intervention\Image\Colors\Rgb\Channels\Green
 * @covers \Intervention\Image\Colors\Rgb\Channels\Blue
 */
class ChannelTest extends TestCase
{
    public function testConstructor(): void
    {
        $channel = new Channel(0);
        $this->assertInstanceOf(Channel::class, $channel);

        $channel = new Channel(value: 0);
        $this->assertInstanceOf(Channel::class, $channel);

        $channel = new Channel(normalized: 0);
        $this->assertInstanceOf(Channel::class, $channel);

        $this->expectException(ColorException::class);
        $channel = new Channel();

        $this->expectException(ColorException::class);
        $channel = new Channel(normalized: 2);
    }

    public function testConstructorFail(): void
    {
        $this->expectException(ColorException::class);
        new Channel(300);
    }

    public function testToInt(): void
    {
        $channel = new Channel(255);
        $this->assertEquals(255, $channel->toInt());
    }

    public function testToString(): void
    {
        $channel = new Channel(10);
        $this->assertEquals("10", $channel->toString());
        $this->assertEquals("10", (string) $channel);
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
}
