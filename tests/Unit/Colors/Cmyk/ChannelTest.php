<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Cmyk;

use Generator;
use Intervention\Image\Colors\Cmyk\Channels\Cyan;
use Intervention\Image\Colors\Cmyk\Channels\Key;
use Intervention\Image\Colors\Cmyk\Channels\Magenta;
use Intervention\Image\Colors\Cmyk\Channels\Yellow;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

#[CoversClass(Cyan::class)]
#[CoversClass(Magenta::class)]
#[CoversClass(Yellow::class)]
#[CoversClass(Key::class)]
final class ChannelTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $channel = new Cyan(0);
        $this->assertInstanceOf(Cyan::class, $channel);

        $channel = new Cyan(value: 0);
        $this->assertInstanceOf(Cyan::class, $channel);

        $channel = Cyan::fromNormalized(0);
        $this->assertInstanceOf(Cyan::class, $channel);
    }

    public function testConstructorFailInvalidArgument(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Cyan(200);
    }

    public function testConstructorFailInvalidArgumentNormalized(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Cyan::fromNormalized(2);
    }

    public function testToString(): void
    {
        $channel = new Cyan(10);
        $this->assertEquals("10", $channel->toString());
        $this->assertEquals("10", (string) $channel);
    }

    public function testValue(): void
    {
        $channel = new Cyan(10);
        $this->assertEquals(10, $channel->value());
    }

    public function testNormalize(): void
    {
        $channel = new Cyan(100);
        $this->assertEquals(1, $channel->normalizedValue());
        $channel = new Cyan(0);
        $this->assertEquals(0, $channel->normalizedValue());
        $channel = new Cyan(20);
        $this->assertEquals(.2, $channel->normalizedValue());
    }

    public function testValidate(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Cyan(101);

        $this->expectException(InvalidArgumentException::class);
        new Cyan(-1);
    }

    #[DataProvider('scaleDataProvider')]
    public function testScale(int $value, int $percent, int $result): void
    {
        $this->assertEquals($result, (new Cyan($value))->scale($percent)->value());
        $this->assertEquals($result, (new Magenta($value))->scale($percent)->value());
        $this->assertEquals($result, (new Yellow($value))->scale($percent)->value());
        $this->assertEquals($result, (new Key($value))->scale($percent)->value());
    }

    public static function scaleDataProvider(): Generator
    {
        yield [0, 0, 0];
        yield [100, 0, 100];
        yield [50, 0, 50];

        yield [0, 50, 50];
        yield [100, 50, 100];
        yield [50, 50, 75];

        yield [0, 100, 100];
        yield [100, 100, 100];
        yield [50, 100, 100];

        yield [0, -50, 0];
        yield [100, -50, 50];
        yield [50, -50, 25];

        yield [0, -100, 0];
        yield [100, -100, 0];
        yield [50, -100, 0];
    }
}
