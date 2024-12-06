<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Rgb;

use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\Rgb\Channels\Red as Channel;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Tests\BaseTestCase;

#[CoversClass(Red::class)]
#[CoversClass(Green::class)]
#[CoversClass(Blue::class)]
final class ChannelTest extends BaseTestCase
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
        new Channel();

        $this->expectException(ColorException::class);
        new Channel(normalized: 2);
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
