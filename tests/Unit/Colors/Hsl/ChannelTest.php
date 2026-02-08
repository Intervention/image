<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Hsl;

use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\Hsl\Channels\Hue;
use Intervention\Image\Colors\Hsl\Channels\Saturation;
use Intervention\Image\Colors\Hsl\Channels\Luminance;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

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

        $channel = Hue::fromNormalized(0);
        $this->assertInstanceOf(Hue::class, $channel);
    }

    public function testConstructorFailInvalidArgument(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Hue(400);
    }

    public function testConstructorFailInvalidArgumentNormalized(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Hue::fromNormalized(2);
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
        $this->assertEquals(1, $channel->normalizedValue());
        $channel = new Hue(180);
        $this->assertEquals(0.5, $channel->normalizedValue());
        $channel = new Hue(0);
        $this->assertEquals(0, $channel->normalizedValue());
        $channel = new Luminance(90);
        $this->assertEquals(.9, $channel->normalizedValue());
    }

    public function testValidate(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Hue(361);

        $this->expectException(InvalidArgumentException::class);
        new Hue(-1);

        $this->expectException(InvalidArgumentException::class);
        new Saturation(101);

        $this->expectException(InvalidArgumentException::class);
        new Saturation(-1);

        $this->expectException(InvalidArgumentException::class);
        new Luminance(101);

        $this->expectException(InvalidArgumentException::class);
        new Luminance(-1);
    }

    #[DataProvider('scaleDataProvider')]
    public function testScale(int $value, int $percent, int $result): void
    {
        $this->assertEquals($result, (new Hue($value))->scale($percent)->value());
    }

    public static function scaleDataProvider(): Generator
    {
        yield [0, 0, 0];
        yield [360, 0, 360];
        yield [180, 0, 180];

        yield [0, 50, 180];
        yield [360, 50, 360];
        yield [180, 50, 270];

        yield [0, 100, 360];
        yield [360, 100, 360];
        yield [180, 100, 360];

        yield [0, -50, 0];
        yield [360, -50, 180];
        yield [180, -50, 90];

        yield [0, -100, 0];
        yield [360, -100, 0];
        yield [180, -100, 0];
    }
}
