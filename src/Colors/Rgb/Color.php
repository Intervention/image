<?php

namespace Intervention\Image\Colors\Rgb;

use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Colors\Rgb\Channels\Alpha;
use Intervention\Image\Colors\Traits\CanHandleChannels;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;

class Color implements ColorInterface
{
    use CanHandleChannels;

    protected array $channels;

    public function __construct(int $r, int $g, int $b, int $a = 255)
    {
        $this->channels = [
            new Red($r),
            new Green($g),
            new Blue($b),
            new Alpha($a),
        ];
    }

    public function red(): ColorChannelInterface
    {
        return $this->channel(Red::class);
    }

    public function green(): ColorChannelInterface
    {
        return $this->channel(Green::class);
    }

    public function blue(): ColorChannelInterface
    {
        return $this->channel(Blue::class);
    }

    public function alpha(): ColorChannelInterface
    {
        return $this->channel(Alpha::class);
    }

    public function toArray(): array
    {
        return array_map(function (ColorChannelInterface $channel) {
            return $channel->value();
        }, $this->channels());
    }

    public function toHex(string $prefix = ''): string
    {
        if ($this->isFullyOpaque()) {
            return sprintf(
                '%s%02x%02x%02x',
                $prefix,
                $this->red()->value(),
                $this->green()->value(),
                $this->blue()->value()
            );
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

    public function convertTo(string|ColorspaceInterface $colorspace): ColorInterface
    {
        $colorspace = match (true) {
            is_object($colorspace) => $colorspace,
            default => new $colorspace(),
        };

        return $colorspace->convertColor($this);
    }

    public function isFullyOpaque(): bool
    {
        return $this->alpha()->value() === 255;
    }

    public function toString(): string
    {
        if ($this->isFullyOpaque()) {
            return sprintf(
                'rgb(%d, %d, %d)',
                $this->red()->value(),
                $this->green()->value(),
                $this->blue()->value()
            );
        }

        return sprintf(
            'rgba(%d, %d, %d, %.1F)',
            $this->red()->value(),
            $this->green()->value(),
            $this->blue()->value(),
            $this->alpha()->normalize(),
        );
    }

    public function isGreyscale(): bool
    {
        $values = [$this->red()->value(), $this->green()->value(), $this->blue()->value()];

        return count(array_unique($values, SORT_REGULAR)) === 1;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
