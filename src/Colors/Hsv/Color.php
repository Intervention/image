<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Hsv;

use Intervention\Image\Colors\AbstractColor;
use Intervention\Image\Colors\Hsv\Channels\Hue;
use Intervention\Image\Colors\Hsv\Channels\Saturation;
use Intervention\Image\Colors\Hsv\Channels\Value;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Drivers\AbstractInputHandler;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;

class Color extends AbstractColor
{
    public function __construct(int $h, int $s, int $v)
    {
        $this->channels = [
            new Hue($h),
            new Saturation($s),
            new Value($v),
        ];
    }

    public function colorspace(): ColorspaceInterface
    {
        return new Colorspace();
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::create()
     */
    public static function create(mixed $input): ColorInterface
    {
        return (new class ([
            Decoders\StringColorDecoder::class,
        ]) extends AbstractInputHandler
        {
        })->handle($input);
    }

    /**
     * Return the Hue channel
     *
     * @return ColorChannelInterface
     */
    public function hue(): ColorChannelInterface
    {
        return $this->channel(Hue::class);
    }

    /**
     * Return the Saturation channel
     *
     * @return ColorChannelInterface
     */
    public function saturation(): ColorChannelInterface
    {
        return $this->channel(Saturation::class);
    }

    /**
     * Return the Value channel
     *
     * @return ColorChannelInterface
     */
    public function value(): ColorChannelInterface
    {
        return $this->channel(Value::class);
    }

    public function toHex(string $prefix = ''): string
    {
        return $this->convertTo(RgbColorspace::class)->toHex($prefix);
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::toString()
     */
    public function toString(): string
    {
        return sprintf(
            'hsv(%d, %d%%, %d%%)',
            $this->hue()->value(),
            $this->saturation()->value(),
            $this->value()->value()
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::isGreyscale()
     */
    public function isGreyscale(): bool
    {
        return $this->saturation()->value() == 0;
    }
}
