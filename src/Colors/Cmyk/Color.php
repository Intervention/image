<?php

namespace Intervention\Image\Colors\Cmyk;

use Intervention\Image\Colors\Cmyk\Channels\Cyan;
use Intervention\Image\Colors\Cmyk\Channels\Magenta;
use Intervention\Image\Colors\Cmyk\Channels\Yellow;
use Intervention\Image\Colors\Cmyk\Channels\Key;
use Intervention\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Colors\Rgba\Colorspace as RgbaColorspace;
use Intervention\Image\Colors\Rgba\Color as RgbaColor;
use Intervention\Image\Colors\Traits\CanHandleChannels;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;

class Color implements ColorInterface
{
    use CanHandleChannels;

    protected array $channels;

    public function __construct(int $c, int $m, int $y, int $k)
    {
        $this->channels = [
            new Cyan($c),
            new Magenta($m),
            new Yellow($y),
            new Key($k),
        ];
    }

    public function channels(): array
    {
        return $this->channels;
    }

    public function cyan(): Cyan
    {
        return $this->channel(Cyan::class);
    }

    public function magenta(): Magenta
    {
        return $this->channel(Magenta::class);
    }

    public function yellow(): Yellow
    {
        return $this->channel(Yellow::class);
    }

    public function key(): Key
    {
        return $this->channel(Key::class);
    }

    public function toArray(): array
    {
        return [
            $this->cyan()->value(),
            $this->magenta()->value(),
            $this->yellow()->value(),
            $this->key()->value(),
        ];
    }

    public function convertTo(string|ColorspaceInterface $colorspace): ColorInterface
    {
        $colorspace = match (true) {
            is_object($colorspace) => $colorspace,
            default => new $colorspace(),
        };

        return $colorspace->convertColor($this);
    }

    public function toRgb(): RgbColor
    {
        return $this->convertTo(RgbColorspace::class);
    }

    public function toRgba(): RgbaColor
    {
        return $this->convertTo(RgbaColorspace::class);
    }

    public function toCmyk(): self
    {
        return $this->convertTo(CmykColorspace::class);
    }

    public function toString(): string
    {
        return sprintf(
            'cmyk(%d, %d, %d, %d)',
            $this->cyan()->value(),
            $this->magenta()->value(),
            $this->yellow()->value(),
            $this->key()->value()
        );
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
