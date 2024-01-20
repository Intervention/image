<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Colors\Cmyk;

use Intervention\Image\Colors\Cmyk\Channels\Cyan as Channel;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Colors\Cmyk\Channels\Cyan
 * @covers \Intervention\Image\Colors\Cmyk\Channels\Magenta
 * @covers \Intervention\Image\Colors\Cmyk\Channels\Yellow
 * @covers \Intervention\Image\Colors\Cmyk\Channels\Key
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
        new Channel(200);
    }

    public function testToInt(): void
    {
        $channel = new Channel(10);
        $this->assertEquals(10, $channel->toInt());
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
        $channel = new Channel(100);
        $this->assertEquals(1, $channel->normalize());
        $channel = new Channel(0);
        $this->assertEquals(0, $channel->normalize());
        $channel = new Channel(20);
        $this->assertEquals(.2, $channel->normalize());
    }

    public function testValidate(): void
    {
        $this->expectException(ColorException::class);
        new Channel(101);

        $this->expectException(ColorException::class);
        new Channel(-1);
    }
}
