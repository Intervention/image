<?php

namespace Intervention\Image\Colors\Cmyk;

use Intervention\Image\Colors\Cmyk\Channels\Cyan;
use Intervention\Image\Colors\Cmyk\Channels\Magenta;
use Intervention\Image\Colors\Cmyk\Channels\Yellow;
use Intervention\Image\Colors\Cmyk\Channels\Key;
use Intervention\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;

class Color implements ColorInterface
{
    protected array $channels;

    protected Cyan $cyan;
    protected Magenta $magenta;
    protected Yellow $yellow;
    protected Key $key;

    public function __construct(int $c, int $m, int $y, int $k)
    {
        $this->cyan = new Cyan($c);
        $this->magenta = new Magenta($m);
        $this->yellow = new Yellow($y);
        $this->key = new Key($k);
    }

    public function channels(): array
    {
        return $this->channels;
    }

    public function cyan(): Cyan
    {
        return $this->cyan;
    }

    public function magenta(): Magenta
    {
        return $this->magenta;
    }

    public function yellow(): Yellow
    {
        return $this->yellow;
    }

    public function key(): Key
    {
        return $this->key;
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

    public function transformTo(string|ColorspaceInterface $colorspace): ColorInterface
    {
        $colorspace = match (true) {
            is_object($colorspace) => $colorspace,
            default => new $colorspace(),
        };

        return $colorspace->transformColor($this);
    }

    public function toRgb(): RgbColor
    {
        return $this->transformTo(RgbColorspace::class);
    }

    public function toCmyk(): self
    {
        return $this->transformTo(CmykColorspace::class);
    }

    public function __toString(): string
    {
        return sprintf(
            'cmyk(%d, %d, %d, %d)',
            $this->cyan()->value(),
            $this->magenta()->value(),
            $this->yellow()->value(),
            $this->key()->value()
        );
    }
}
