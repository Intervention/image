<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Cmyk;

use Intervention\Image\Colors\Cmyk\Channels\Cyan;
use Intervention\Image\Colors\Cmyk\Channels\Key;
use Intervention\Image\Colors\Cmyk\Channels\Magenta;
use Intervention\Image\Colors\Cmyk\Channels\Yellow;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

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

        $channel = new Cyan(normalized: 0);
        $this->assertInstanceOf(Cyan::class, $channel);

        $this->expectException(ColorException::class);
         new Cyan();

        $this->expectException(ColorException::class);
         new Cyan(normalized: 2);
    }

    public function testConstructorFail(): void
    {
        $this->expectException(ColorException::class);
         new Cyan(200);
    }

    public function testToInt(): void
    {
        $channel = new Cyan(10);
        $this->assertEquals(10, $channel->toInt());
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
        $this->assertEquals(1, $channel->normalize());
        $channel = new Cyan(0);
        $this->assertEquals(0, $channel->normalize());
        $channel = new Cyan(20);
        $this->assertEquals(.2, $channel->normalize());
    }

    public function testValidate(): void
    {
        $this->expectException(ColorException::class);
         new Cyan(101);

        $this->expectException(ColorException::class);
         new Cyan(-1);
    }
}
