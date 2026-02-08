<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Rgb;

use Generator;
use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\Rgb\Channels\Red as Channel;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

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

        $this->expectException(InvalidArgumentException::class);
        new Channel(-1);
    }

    #[DataProvider('scaleDataProvider')]
    public function testScale(int $value, int $percent, int $result): void
    {
        $this->assertEquals($result, (new Red($value))->scale($percent)->value());
        $this->assertEquals($result, (new Green($value))->scale($percent)->value());
        $this->assertEquals($result, (new Blue($value))->scale($percent)->value());
    }

    public static function scaleDataProvider(): Generator
    {
        yield [0, 0, 0];
        yield [255, 0, 255];
        yield [55, 0, 55];
        yield [0, 50, 128];
        yield [255, 50, 255];
        yield [100, 50, 178];
        yield [100, 100, 255];
        yield [0, -50, 0];
        yield [255, -50, 127];
        yield [100, -50, 50];
        yield [100, -100, 0];
    }
}
