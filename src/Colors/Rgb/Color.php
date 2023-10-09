<?php

namespace Intervention\Image\Colors\Rgb;

use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;

class Color implements ColorInterface
{
    protected array $channels;

    public function __construct(int $r, int $g, int $b)
    {
        $this->channels = [
            new Red($r),
            new Green($g),
            new Blue($b),
        ];
    }

    public function channels(): array
    {
        return $this->channels;
    }

    public function channel(string $classname): ColorChannelInterface
    {
        $channels = array_filter($this->channels(), function (ColorChannelInterface $channel) use ($classname) {
            return is_a($channel, $classname);
        });

        return reset($channels);
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

    public function toArray(): array
    {
        return array_map(function (ColorChannelInterface $channel) {
            return $channel->value();
        }, $this->channels());
    }

    public function normalize(): array
    {
        return array_map(function (ColorChannelInterface $channel) {
            return $channel->normalize();
        }, $this->channels());
    }

    public function toHex(string $prefix = ''): string
    {
        return sprintf(
            '%s%02x%02x%02x',
            $prefix,
            $this->red()->value(),
            $this->green()->value(),
            $this->blue()->value()
        );
    }

    public function toRgb(): Color
    {
        return $this;
    }

    public function toInt(): int
    {
        return $this->red()->value() * 256 * 256 + $this->green()->value() * 256 + $this->blue()->value();
    }

    public function transformTo(string|ColorspaceInterface $colorspace): ColorInterface
    {
        $colorspace = match (true) {
            is_object($colorspace) => $colorspace,
            default => new $colorspace(),
        };

        return $colorspace->transformColor($this);
    }

    public function toCmyk(): CmykColor
    {
        return $this->transformTo(CmykColorspace::class);
    }

    public function __toString(): string
    {
        return sprintf(
            'rgb(%d, %d, %d)',
            $this->red()->value(),
            $this->green()->value(),
            $this->blue()->value()
        );
    }
}
