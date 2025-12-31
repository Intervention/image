<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Oklab;

use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\Oklab\Channels\Lightness;
use Intervention\Image\Colors\Oklab\Channels\A;
use Intervention\Image\Colors\Oklab\Channels\B;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Tests\BaseTestCase;

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
        $this->assertEquals(1, $channel->normalize());
        $channel = new Lightness(0);
        $this->assertEquals(0, $channel->normalize());
        $channel = new Lightness(.5);
        $this->assertEquals(.5, $channel->normalize());
    }
}
