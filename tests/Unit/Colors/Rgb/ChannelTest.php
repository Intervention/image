<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Rgb;

use Intervention\Image\Colors\IntegerColorChannel;
use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\Rgb\Channels\Red as Channel;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Tests\BaseTestCase;

#[CoversClass(Red::class)]
#[CoversClass(Green::class)]
#[CoversClass(Blue::class)]
#[CoversClass(IntegerColorChannel::class)]
final class ChannelTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $channel = new Channel(0);
        $this->assertInstanceOf(Channel::class, $channel);

        $channel = new Channel(value: 0);
        $this->assertInstanceOf(Channel::class, $channel);

        $channel = Channel::fromNormalized(0);
        $this->assertInstanceOf(Channel::class, $channel);
    }

    public function testConstructorFailInvalidArgument(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Channel(300);
    }

    public function testConstructorFailInvalidArgumentNormalized(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Channel::fromNormalized(2);
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
        $this->assertEquals(1, $channel->normalizedValue());
        $channel = new Channel(0);
        $this->assertEquals(0, $channel->normalizedValue());
        $channel = new Channel(51);
        $this->assertEquals(.2, $channel->normalizedValue());
    }

    public function testValidate(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Channel(256);
    }

    public function testValidateNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Channel(-1);
    }

    public function testScalePositive(): void
    {
        $channel = new Channel(100);
        $result = $channel->scale(50);
        $this->assertSame($channel, $result);
        // base = (255 - 100) = 155, delta = round(155/100*50) = 78, value = 100+78 = 178
        $this->assertEquals(178, $channel->value());
    }

    public function testScaleNegative(): void
    {
        $channel = new Channel(200);
        $result = $channel->scale(-50);
        $this->assertSame($channel, $result);
        // base = 200, delta = round(200/100*-50) = -100, value = 200-100 = 100
        $this->assertEquals(100, $channel->value());
    }

    public function testScaleZero(): void
    {
        $channel = new Channel(100);
        $result = $channel->scale(0);
        $this->assertSame($channel, $result);
        $this->assertEquals(100, $channel->value());
    }

    public function testScaleMax(): void
    {
        $channel = new Channel(100);
        $channel->scale(100);
        // base = (255 - 100) = 155, delta = round(155/100*100) = 155, value = 100+155 = 255
        $this->assertEquals(255, $channel->value());
    }

    public function testScaleMinNegative(): void
    {
        $channel = new Channel(100);
        $channel->scale(-100);
        // base = 100, delta = round(100/100*-100) = -100, value = 100-100 = 0
        $this->assertEquals(0, $channel->value());
    }

    public function testScaleFailsOverRange(): void
    {
        $channel = new Channel(100);
        $this->expectException(InvalidArgumentException::class);
        $channel->scale(101);
    }

    public function testScaleFailsUnderRange(): void
    {
        $channel = new Channel(100);
        $this->expectException(InvalidArgumentException::class);
        $channel->scale(-101);
    }

    public function testFromNormalizedBoundaries(): void
    {
        $channel = Channel::fromNormalized(0);
        $this->assertEquals(0, $channel->value());

        $channel = Channel::fromNormalized(1);
        $this->assertEquals(255, $channel->value());

        $channel = Channel::fromNormalized(0.5);
        $this->assertEquals(128, $channel->value());
    }

    public function testFromNormalizedFailsNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Channel::fromNormalized(-0.1);
    }
}
