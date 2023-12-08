<?php

namespace Intervention\Image\Colors\Cmyk;

use Intervention\Image\Colors\AbstractColor;
use Intervention\Image\Colors\Cmyk\Channels\Cyan;
use Intervention\Image\Colors\Cmyk\Channels\Magenta;
use Intervention\Image\Colors\Cmyk\Channels\Yellow;
use Intervention\Image\Colors\Cmyk\Channels\Key;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Drivers\AbstractInputHandler;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;

class Color extends AbstractColor
{
    public function __construct(int $c, int $m, int $y, int $k)
    {
        $this->channels = [
            new Cyan($c),
            new Magenta($m),
            new Yellow($y),
            new Key($k),
        ];
    }

    public static function create(mixed $input): ColorInterface
    {
        return (new class ([
            Decoders\StringColorDecoder::class,
        ]) extends AbstractInputHandler
        {
        })->handle($input);
    }

    public function colorspace(): ColorspaceInterface
    {
        return new Colorspace();
    }

    public function toHex(string $prefix = ''): string
    {
        return $this->convertTo(RgbColorspace::class)->toHex($prefix);
    }

    public function cyan(): ColorChannelInterface
    {
        return $this->channel(Cyan::class);
    }

    public function magenta(): ColorChannelInterface
    {
        return $this->channel(Magenta::class);
    }

    public function yellow(): ColorChannelInterface
    {
        return $this->channel(Yellow::class);
    }

    public function key(): ColorChannelInterface
    {
        return $this->channel(Key::class);
    }

    public function toString(): string
    {
        return sprintf(
            'cmyk(%d%%, %d%%, %d%%, %d%%)',
            $this->cyan()->value(),
            $this->magenta()->value(),
            $this->yellow()->value(),
            $this->key()->value()
        );
    }

    public function isGreyscale(): bool
    {
        return 0 === array_sum([
            $this->cyan()->value(),
            $this->magenta()->value(),
            $this->yellow()->value(),
        ]);
    }
}
