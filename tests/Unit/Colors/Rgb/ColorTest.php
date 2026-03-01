<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Rgb;

use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Colors\AbstractColor;
use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Intervention\Image\Colors\Rgb\Channels\Alpha;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Exceptions\ColorDecoderException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Tests\BaseTestCase;

#[CoversClass(Color::class)]
#[CoversClass(AbstractColor::class)]
final class ColorTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $color = new Color(0, 0, 0);
        $this->assertInstanceOf(Color::class, $color);

        $color = new Color(0, 0, 0, 0);
        $this->assertInstanceOf(Color::class, $color);
    }

    public function testCreate(): void
    {
        $color = Color::create('ccc');
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals(
            [204, 204, 204, 255],
            array_map(
                fn(ColorChannelInterface $channel): int => $channel->value(),
                $color->channels(),
            ),
        );

        $color = Color::create('rgba(10, 20, 30, .2)');
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals(
            [10, 20, 30, 51],
            array_map(
                fn(ColorChannelInterface $channel): int => $channel->value(),
                $color->channels(),
            ),
        );

        $color = Color::create(10, 20, 30, .2);
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals(
            [10, 20, 30, 51],
            array_map(
                fn(ColorChannelInterface $channel): int => $channel->value(),
                $color->channels(),
            ),
        );
    }

    public function testColorspace(): void
    {
        $color = new Color(0, 0, 0);
        $this->assertInstanceOf(RgbColorspace::class, $color->colorspace());
    }

    public function testChannels(): void
    {
        $color = new Color(10, 20, 30);
        $this->assertIsArray($color->channels());
        $this->assertCount(4, $color->channels());
    }

    public function testChannel(): void
    {
        $color = new Color(10, 20, 30);
        $channel = $color->channel(Red::class);
        $this->assertInstanceOf(Red::class, $channel);
        $this->assertEquals(10, $channel->value());
    }

    public function testChannelNotFound(): void
    {
        $color = new Color(10, 20, 30);
        $this->expectException(NotSupportedException::class);
        $color->channel('none');
    }

    public function testRedGreenBlue(): void
    {
        $color = new Color(10, 20, 30);
        $this->assertInstanceOf(Red::class, $color->red());
        $this->assertInstanceOf(Green::class, $color->green());
        $this->assertInstanceOf(Blue::class, $color->blue());
        $this->assertEquals(10, $color->red()->value());
        $this->assertEquals(20, $color->green()->value());
        $this->assertEquals(30, $color->blue()->value());
    }

    public function testToHex(): void
    {
        $color = new Color(181, 55, 23);
        $this->assertEquals('b53717', $color->toHex());
        $this->assertEquals('#b53717', $color->toHex('#'));

        $color = new Color(181, 55, 23, .2);
        $this->assertEquals('b5371733', $color->toHex());
    }

    public function testNormalize(): void
    {
        $color = new Color(255, 0, 51);
        $this->assertEquals(
            [1.0, 0.0, 0.2, 1.0],
            array_map(
                fn(ColorChannelInterface $channel): float => $channel->normalizedValue(),
                $color->channels(),
            )
        );

        $color = new Color(255, 0, 51, 1);
        $this->assertEquals(
            [1.0, 0.0, 0.2, 1.0],
            array_map(
                fn(ColorChannelInterface $channel): float => $channel->normalizedValue(),
                $color->channels(),
            )
        );

        $color = new Color(255, 0, 51, .2);
        $this->assertEquals(
            [1.0, 0.0, 0.2, .2],
            array_map(
                fn(ColorChannelInterface $channel): float => $channel->normalizedValue(),
                $color->channels(),
            )
        );
    }

    public function testToString(): void
    {
        $color = new Color(181, 55, 23);
        $this->assertEquals('rgb(181 55 23)', (string) $color);

        $color = new Color(181, 55, 23, 1);
        $this->assertEquals('rgb(181 55 23)', (string) $color);

        $color = new Color(181, 55, 23, .2);
        $this->assertEquals('rgb(181 55 23 / 0.2)', (string) $color);

        $color = new Color(181, 55, 23, 0);
        $this->assertEquals('rgb(181 55 23 / 0)', (string) $color);
    }

    public function testToColorspace(): void
    {
        $color = new Color(0, 0, 0);
        $converted = $color->toColorspace(CmykColorspace::class);
        $this->assertInstanceOf(CmykColor::class, $converted);
        $this->assertEquals(
            [0, 0, 0, 100, 255],
            array_map(
                fn(ColorChannelInterface $channel): int => $channel->value(),
                $converted->channels()
            )
        );

        $color = new Color(255, 255, 255);
        $converted = $color->toColorspace(CmykColorspace::class);
        $this->assertInstanceOf(CmykColor::class, $converted);
        $this->assertEquals(
            [0, 0, 0, 0, 255],
            array_map(
                fn(ColorChannelInterface $channel): int => $channel->value(),
                $converted->channels()
            )
        );

        $color = new Color(255, 0, 0);
        $converted = $color->toColorspace(CmykColorspace::class);
        $this->assertInstanceOf(CmykColor::class, $converted);
        $this->assertEquals(
            [0, 100, 100, 0, 255],
            array_map(
                fn(ColorChannelInterface $channel): int => $channel->value(),
                $converted->channels()
            )
        );

        $color = new Color(255, 0, 255);
        $converted = $color->toColorspace(CmykColorspace::class);
        $this->assertInstanceOf(CmykColor::class, $converted);
        $this->assertEquals(
            [0, 100, 0, 0, 255],
            array_map(
                fn(ColorChannelInterface $channel): int => $channel->value(),
                $converted->channels()
            )
        );

        $color = new Color(255, 255, 0);
        $converted = $color->toColorspace(CmykColorspace::class);
        $this->assertInstanceOf(CmykColor::class, $converted);
        $this->assertEquals(
            [0, 0, 100, 0, 255],
            array_map(
                fn(ColorChannelInterface $channel): int => $channel->value(),
                $converted->channels()
            )
        );

        $color = new Color(255, 204, 204);
        $converted = $color->toColorspace(CmykColorspace::class);
        $this->assertInstanceOf(CmykColor::class, $converted);
        $this->assertEquals(
            [0, 20, 20, 0, 255],
            array_map(
                fn(ColorChannelInterface $channel): int => $channel->value(),
                $converted->channels()
            )
        );
    }

    public function testIsGrayscale(): void
    {
        $color = new Color(255, 0, 100);
        $this->assertFalse($color->isGrayscale());

        $color = new Color(50, 50, 50);
        $this->assertTrue($color->isGrayscale());
    }

    public function testIsTransparent(): void
    {
        $color = new Color(255, 255, 255);
        $this->assertFalse($color->isTransparent());

        $color = new Color(255, 255, 255, 1);
        $this->assertFalse($color->isTransparent());

        $color = new Color(255, 255, 255, .5);
        $this->assertTrue($color->isTransparent());

        $color = new Color(255, 255, 255, 0);
        $this->assertTrue($color->isTransparent());
    }

    public function testIsClear(): void
    {
        $color = new Color(255, 255, 255);
        $this->assertFalse($color->isClear());

        $color = new Color(255, 255, 255, 1);
        $this->assertFalse($color->isClear());

        $color = new Color(255, 255, 255, .1);
        $this->assertFalse($color->isClear());

        $color = new Color(255, 255, 255, 0);
        $this->assertTrue($color->isClear());
    }

    public function testSetTransparency(): void
    {
        $color = new Color(0, 0, 0, 1);
        $result = $color->withTransparency(.2);
        $this->assertEquals(255, $color->channel(Alpha::class)->value());
        $this->assertEquals(51, $result->channel(Alpha::class)->value());
    }

    public function testDebugInfo(): void
    {
        $info = (new Color(10, 20, 30, .2))->__debugInfo();
        $this->assertEquals('10', $info['red']);
        $this->assertEquals('20', $info['green']);
        $this->assertEquals('30', $info['blue']);
        $this->assertEquals('0.2', $info['alpha']);
    }

    public function testCreateFailsInvalidArgumentCount(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Color::create(10, 20);
    }

    public function testCreateFailsInvalidString(): void
    {
        $this->expectException(ColorDecoderException::class);
        Color::create('not-a-color');
    }

    public function testCreateWithFourArgs(): void
    {
        $color = Color::create(10, 20, 30, .5);
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals(10, $color->red()->value());
        $this->assertEquals(20, $color->green()->value());
        $this->assertEquals(30, $color->blue()->value());
        $this->assertEquals(128, $color->alpha()->value());
    }

    public function testToColorspaceFailsInvalidClass(): void
    {
        $color = new Color(0, 0, 0);
        $this->expectException(InvalidArgumentException::class);
        $color->toColorspace('NonExistentColorspace');
    }

    public function testToColorspaceFailsNonColorspaceClass(): void
    {
        $color = new Color(0, 0, 0);
        $this->expectException(InvalidArgumentException::class);
        $color->toColorspace(\stdClass::class);
    }

    public function testCloneDeepCopiesChannels(): void
    {
        $original = new Color(100, 150, 200);
        $cloned = clone $original;

        $this->assertEquals(100, $original->red()->value());
        $this->assertEquals(100, $cloned->red()->value());

        // Verify they are separate objects (deep clone)
        $this->assertNotSame($original->red(), $cloned->red());
    }

    public function testConstructorWithChannelObjects(): void
    {
        $color = new Color(new Red(10), new Green(20), new Blue(30), new Alpha(.5));
        $this->assertEquals(10, $color->red()->value());
        $this->assertEquals(20, $color->green()->value());
        $this->assertEquals(30, $color->blue()->value());
        $this->assertEquals(128, $color->alpha()->value());
    }

    public function testWithBrightnessPositive(): void
    {
        $color = new Color(255, 0, 0);
        $result = $color->withBrightness(50);
        $this->assertInstanceOf(Color::class, $result);
        $this->assertNotSame($color, $result);

        // Original should be unchanged (immutability)
        $this->assertEquals(255, $color->channel(Red::class)->value());
        $this->assertEquals(0, $color->channel(Green::class)->value());
        $this->assertEquals(0, $color->channel(Blue::class)->value());

        // Lightened result should have higher channel values overall
        $originalChannels = array_map(
            fn(ColorChannelInterface $channel): int => $channel->value(),
            $color->channels(),
        );
        $resultChannels = array_map(
            fn(ColorChannelInterface $channel): int => $channel->value(),
            $result->channels(),
        );
        $this->assertGreaterThan(
            $originalChannels[1] + $originalChannels[2],
            $resultChannels[1] + $resultChannels[2],
        );
    }

    public function testWithBrightnessZero(): void
    {
        $color = new Color(100, 150, 200);
        $result = $color->withBrightness(0);
        $this->assertInstanceOf(Color::class, $result);

        // Allow small rounding differences from colorspace roundtrip
        $this->assertEqualsWithDelta(100, $result->channel(Red::class)->value(), 1);
        $this->assertEqualsWithDelta(150, $result->channel(Green::class)->value(), 1);
        $this->assertEqualsWithDelta(200, $result->channel(Blue::class)->value(), 1);
    }

    public function testWithBrightnessPreservesAlpha(): void
    {
        $color = new Color(255, 0, 0, .5);
        $result = $color->withBrightness(20);
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals($color->channel(Alpha::class)->value(), $result->channel(Alpha::class)->value());
    }

    public function testWithBrightnessInvalidLevelAbove(): void
    {
        $color = new Color(255, 0, 0);
        $this->expectException(InvalidArgumentException::class);
        $color->withBrightness(101);
    }

    public function testWithBrightnessInvalidLevelBelow(): void
    {
        $color = new Color(255, 0, 0);
        $this->expectException(InvalidArgumentException::class);
        $color->withBrightness(-101);
    }

    public function testWithBrightnessNegative(): void
    {
        $color = new Color(255, 0, 0);
        $result = $color->withBrightness(-50);
        $this->assertInstanceOf(Color::class, $result);
        $this->assertNotSame($color, $result);

        // Darkened red: red channel should be lower
        $this->assertLessThan(
            $color->channel(Red::class)->value(),
            $result->channel(Red::class)->value(),
        );
    }

    public function testWithSaturationPositive(): void
    {
        // Start with a desaturated color
        $color = new Color(150, 100, 100);
        $result = $color->withSaturation(50);
        $this->assertInstanceOf(Color::class, $result);
        $this->assertNotSame($color, $result);

        // Saturating should increase difference between max and min channels
        $origDiff = $color->channel(Red::class)->value() - $color->channel(Green::class)->value();
        $resultDiff = $result->channel(Red::class)->value() - $result->channel(Green::class)->value();
        $this->assertGreaterThan($origDiff, $resultDiff);
    }

    public function testWithSaturationInvalidLevelAbove(): void
    {
        $color = new Color(255, 0, 0);
        $this->expectException(InvalidArgumentException::class);
        $color->withSaturation(101);
    }

    public function testWithSaturationInvalidLevelBelow(): void
    {
        $color = new Color(255, 0, 0);
        $this->expectException(InvalidArgumentException::class);
        $color->withSaturation(-101);
    }

    public function testWithSaturationNegative(): void
    {
        $color = new Color(255, 0, 0);
        $result = $color->withSaturation(-50);
        $this->assertInstanceOf(Color::class, $result);
        $this->assertNotSame($color, $result);

        // Desaturating should reduce difference between channels
        $origDiff = $color->channel(Red::class)->value() - $color->channel(Green::class)->value();
        $resultDiff = $result->channel(Red::class)->value() - $result->channel(Green::class)->value();
        $this->assertLessThan($origDiff, $resultDiff);
    }

    public function testWithSaturationFullNegative(): void
    {
        $color = new Color(255, 0, 0);
        $result = $color->withSaturation(-100);
        $this->assertInstanceOf(Color::class, $result);

        // Fully desaturated should be grayscale (R=G=B)
        $this->assertEquals($result->channel(Red::class)->value(), $result->channel(Green::class)->value());
        $this->assertEquals($result->channel(Green::class)->value(), $result->channel(Blue::class)->value());
    }

    public function testInvert(): void
    {
        $color = new Color(255, 0, 0);
        $result = $color->withInversion();
        $this->assertInstanceOf(Color::class, $result);
        $this->assertNotSame($color, $result);
        $this->assertEquals(0, $result->channel(Red::class)->value());
        $this->assertEquals(255, $result->channel(Green::class)->value());
        $this->assertEquals(255, $result->channel(Blue::class)->value());
    }

    public function testInvertBlack(): void
    {
        $color = new Color(0, 0, 0);
        $result = $color->withInversion();
        $this->assertEquals(255, $result->channel(Red::class)->value());
        $this->assertEquals(255, $result->channel(Green::class)->value());
        $this->assertEquals(255, $result->channel(Blue::class)->value());
    }

    public function testInvertWhite(): void
    {
        $color = new Color(255, 255, 255);
        $result = $color->withInversion();
        $this->assertEquals(0, $result->channel(Red::class)->value());
        $this->assertEquals(0, $result->channel(Green::class)->value());
        $this->assertEquals(0, $result->channel(Blue::class)->value());
    }

    public function testInvertPreservesAlpha(): void
    {
        $color = new Color(255, 0, 0, .5);
        $result = $color->withInversion();
        $this->assertEquals($color->channel(Alpha::class)->value(), $result->channel(Alpha::class)->value());
    }
}
