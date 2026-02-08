<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Oklab;

use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\Oklab\Channels\Lightness;
use Intervention\Image\Colors\Oklab\Channels\A;
use Intervention\Image\Colors\Oklab\Channels\B;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

#[CoversClass(Lightness::class)]
#[CoversClass(A::class)]
#[CoversClass(B::class)]
final class ChannelTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $channel = new Lightness(0);
        $this->assertInstanceOf(Lightness::class, $channel);

        $channel = Lightness::fromNormalized(1);
        $this->assertInstanceOf(Lightness::class, $channel);
    }

    public function testConstructorFailInvalidArgument(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Lightness(300);
    }

    public function testConstructorFailInvalidArgumentNormalized(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Lightness::fromNormalized(2);
    }

    public function testToString(): void
    {
        $channel = new Lightness(1);
        $this->assertEquals("1", $channel->toString());
        $this->assertEquals("1", (string) $channel);
    }

    public function testValue(): void
    {
        $this->assertEquals(1, (new Lightness(1))->value());
    }

    public function testNormalize(): void
    {
        $channel = new Lightness(1);
        $this->assertEquals(1, $channel->normalizedValue());
        $channel = new Lightness(0);
        $this->assertEquals(0, $channel->normalizedValue());
        $channel = new Lightness(.5);
        $this->assertEquals(.5, $channel->normalizedValue());
    }

    #[DataProvider('scaleDataProvider')]
    public function testScale(float $value, int $percent, float $result): void
    {
        $this->assertEquals($result, (new Lightness($value))->scale($percent)->value());
    }

    public static function scaleDataProvider(): Generator
    {
        yield [0, 0, 0];
        yield [1, 0, 1];
        yield [.5, 0, .5];

        yield [0, 50, .5];
        yield [1, 50, 1];
        yield [.5, 50, .75];

        yield [0, 100, 1];
        yield [1, 100, 1];
        yield [.5, 100, 1];

        yield [0, -50, 0];
        yield [1, -50, .5];
        yield [.5, -50, .25];

        yield [0, -100, 0];
        yield [1, -100, 0];
        yield [.5, -100, 0];
    }
}
