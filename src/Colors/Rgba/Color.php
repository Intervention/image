<?php

namespace Intervention\Image\Colors\Rgba;

use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Colors\Rgba\Channels\Blue;
use Intervention\Image\Colors\Rgba\Channels\Green;
use Intervention\Image\Colors\Rgba\Channels\Red;
use Intervention\Image\Colors\Rgba\Channels\Alpha;

class Color extends RgbColor
{
    protected array $channels;

    public function __construct(int $r, int $g, int $b, int $a)
    {
        $this->channels = [
            new Red($r),
            new Green($g),
            new Blue($b),
            new Alpha($a),
        ];
    }

    public function red(): Red
    {
        return $this->channel(Red::class);
    }

    public function green(): Green
    {
        return $this->channel(Green::class);
    }

    public function blue(): Blue
    {
        return $this->channel(Blue::class);
    }

    public function alpha(): Alpha
    {
        return $this->channel(Alpha::class);
    }

    public function isFullyOpaque(): bool
    {
        return $this->alpha()->value() === 255;
    }

    public function toHex(string $prefix = ''): string
    {
        if ($this->isFullyOpaque()) {
            return parent::toHex($prefix);
        }

        return sprintf(
            '%s%02x%02x%02x%02x',
            $prefix,
            $this->red()->value(),
            $this->green()->value(),
            $this->blue()->value(),
            $this->alpha()->value()
        );
    }

    public function toRgb(): RgbColor
    {
        return $this->convertTo(RgbColorspace::class);
    }

    public function toRgba(): self
    {
        return $this;
    }

    public function toCmyk(): CmykColor
    {
        return $this->convertTo(CmykColorspace::class);
    }

    public function __toString(): string
    {
        return sprintf(
            'rgba(%d, %d, %d, %.1F)',
            $this->red()->value(),
            $this->green()->value(),
            $this->blue()->value(),
            $this->alpha()->normalize(),
        );
    }
}
