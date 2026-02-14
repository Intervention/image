<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors\Rgb;

use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Cmyk\Colorspace as Cmyk;
use Intervention\Image\Colors\Hsl\Color as HslColor;
use Intervention\Image\Colors\Hsl\Colorspace as Hsl;
use Intervention\Image\Colors\Rgb\Channels\Alpha;
use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Colors\Rgb\Colorspace as Rgb;
use Intervention\Image\Colors\Rgb\NamedColor;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Providers\ColorDataProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;

#[CoversClass(NamedColor::class)]
class NamedColorTest extends BaseTestCase
{
    #[DataProviderExternal(ColorDataProvider::class, 'rgbNamedColor')]
    public function testCreate(mixed $input, array $channels): void
    {
        $color = NamedColor::create(...$input);
        $this->assertInstanceOf(NamedColor::class, $color);
        $this->assertEquals(
            $channels,
            array_map(fn(ColorChannelInterface $channel): int|float =>
            $channel->value(), $color->channels()),
        );
    }

    public function testColorspace(): void
    {
        $this->assertInstanceOf(Rgb::class, NamedColor::STEELBLUE->colorspace());
    }

    public function testToString(): void
    {
        $this->assertEquals('steelblue', NamedColor::STEELBLUE->toString());
        $this->assertEquals('aliceblue', NamedColor::ALICEBLUE->toString());
    }

    public function testToHex(): void
    {
        $this->assertEquals('4682b4', NamedColor::STEELBLUE->toHex());
        $this->assertEquals('f0f8ff', NamedColor::ALICEBLUE->toHex());
        $this->assertEquals('#4682b4', NamedColor::STEELBLUE->toHex('#'));
        $this->assertEquals('#f0f8ff', NamedColor::ALICEBLUE->toHex('#'));
    }

    public function testChannels(): void
    {
        $this->assertIsArray(NamedColor::STEELBLUE->channels());
        foreach (NamedColor::STEELBLUE->channels() as $channel) {
            $this->assertInstanceOf(ColorChannelInterface::class, $channel);
        }
    }

    public function testChannel(): void
    {
        $this->assertInstanceOf(Red::class, NamedColor::STEELBLUE->channel(Red::class));
        $this->assertInstanceOf(Green::class, NamedColor::STEELBLUE->channel(Green::class));
        $this->assertInstanceOf(Blue::class, NamedColor::STEELBLUE->channel(Blue::class));
        $this->assertInstanceOf(Alpha::class, NamedColor::STEELBLUE->channel(Alpha::class));

        $this->assertEquals(70, NamedColor::STEELBLUE->channel(Red::class)->value());
        $this->assertEquals(130, NamedColor::STEELBLUE->channel(Green::class)->value());
        $this->assertEquals(180, NamedColor::STEELBLUE->channel(Blue::class)->value());
        $this->assertEquals(255, NamedColor::STEELBLUE->channel(Alpha::class)->value());
    }

    public function testAlpha(): void
    {
        $this->assertInstanceOf(Alpha::class, NamedColor::STEELBLUE->alpha());
        $this->assertEquals(255, NamedColor::STEELBLUE->alpha()->value());
    }

    public function testToColorspace(): void
    {
        $this->assertInstanceOf(CmykColor::class, NamedColor::STEELBLUE->toColorspace(Cmyk::class));
        $this->assertInstanceOf(HslColor::class, NamedColor::STEELBLUE->toColorspace(Hsl::class));
    }

    public function testIsGrayscale(): void
    {
        $this->assertFalse(NamedColor::STEELBLUE->isGrayscale());
        $this->assertFalse(NamedColor::FUCHSIA->isGrayscale());
        $this->assertTrue(NamedColor::DIMGRAY->isGrayscale());
        $this->assertTrue(NamedColor::GRAY->isGrayscale());
    }

    public function testIsTransparent(): void
    {
        $this->assertFalse(NamedColor::STEELBLUE->isTransparent());
        $this->assertFalse(NamedColor::FUCHSIA->isTransparent());
        $this->assertFalse(NamedColor::DIMGRAY->isTransparent());
        $this->assertFalse(NamedColor::GRAY->isTransparent());
    }

    public function testIsClear(): void
    {
        $this->assertFalse(NamedColor::STEELBLUE->isClear());
        $this->assertFalse(NamedColor::FUCHSIA->isClear());
        $this->assertFalse(NamedColor::DIMGRAY->isClear());
        $this->assertFalse(NamedColor::GRAY->isClear());
    }

    public function testWithTransparency(): void
    {
        $color = NamedColor::STEELBLUE;
        $this->assertEquals(255, $color->alpha()->value());
        $this->assertEquals(51, $color->withTransparency(.2)->alpha()->value());
    }
}
