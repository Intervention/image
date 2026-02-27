<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Rgb\Channels;

use Intervention\Image\Colors\AlphaChannel;
use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\Rgb\Channels\Alpha;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Tests\BaseTestCase;

#[CoversClass(Alpha::class)]
#[CoversClass(AlphaChannel::class)]
final class AlphaTest extends BaseTestCase
{
    public function testToString(): void
    {
        $alpha = new Alpha(.333333);
        $this->assertEquals('0.33', $alpha->toString());
        $this->assertEquals('0.33', (string) $alpha);
    }

    public function testConstructorDefault(): void
    {
        $alpha = new Alpha();
        $this->assertEquals(255, $alpha->value());
    }

    public function testConstructorFullyOpaque(): void
    {
        $alpha = new Alpha(1);
        $this->assertEquals(255, $alpha->value());
    }

    public function testConstructorFullyTransparent(): void
    {
        $alpha = new Alpha(0);
        $this->assertEquals(0, $alpha->value());
    }

    public function testConstructorHalfTransparent(): void
    {
        $alpha = new Alpha(0.5);
        $this->assertEquals(128, $alpha->value());
    }

    public function testConstructorFailsAboveOne(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Alpha(1.1);
    }

    public function testConstructorFailsBelowZero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Alpha(-0.1);
    }

    public function testFromNormalized(): void
    {
        $alpha = Alpha::fromNormalized(0.5);
        $this->assertEquals(128, $alpha->value());

        $alpha = Alpha::fromNormalized(0);
        $this->assertEquals(0, $alpha->value());

        $alpha = Alpha::fromNormalized(1);
        $this->assertEquals(255, $alpha->value());
    }

    public function testValue(): void
    {
        $alpha = new Alpha(0.2);
        $this->assertEquals(51, $alpha->value());
    }

    public function testMin(): void
    {
        $this->assertEquals(0, Alpha::min());
    }

    public function testMax(): void
    {
        $this->assertEquals(255, Alpha::max());
    }

    public function testNormalizedValue(): void
    {
        $alpha = new Alpha(1);
        $this->assertEquals(1.0, $alpha->normalizedValue());

        $alpha = new Alpha(0);
        $this->assertEquals(0.0, $alpha->normalizedValue());

        $alpha = new Alpha(0.5);
        $this->assertEquals(0.5, $alpha->normalizedValue(1));
    }
}
