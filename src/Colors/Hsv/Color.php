<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Hsv;

use Intervention\Image\Colors\AbstractColor;
use Intervention\Image\Colors\Hsv\Channels\Alpha;
use Intervention\Image\Colors\Hsv\Channels\Hue;
use Intervention\Image\Colors\Hsv\Channels\Saturation;
use Intervention\Image\Colors\Hsv\Channels\Value;
use Intervention\Image\Colors\Hsv\Decoders\StringColorDecoder;
use Intervention\Image\Colors\Rgb\Colorspace as Rgb;
use Intervention\Image\Exceptions\ColorDecoderException;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\InputHandler;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;

class Color extends AbstractColor
{
    /**
     * Create new color object.
     */
    public function __construct(int|Hue $h, int|Saturation $s, int|Value $v, float|Alpha $a = 1)
    {
        $this->channels = [
            is_int($h) ? new Hue($h) : $h,
            is_int($s) ? new Saturation($s) : $s,
            is_int($v) ? new Value($v) : $v,
            is_float($a) ? new Alpha($a) : $a,
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::create()
     *
     * @throws InvalidArgumentException
     * @throws DriverException
     * @throws ColorDecoderException
     */
    public static function create(int|Hue $h, int|Saturation $s, int|Value $v, float|Alpha $a = 1): self
    {
        return new self($h, $s, $v, $a);
    }

    /**
     * Parse HSV color from string.
     */
    public static function parse(string $input): self
    {
        try {
            $color = InputHandler::usingDecoders([
                StringColorDecoder::class,
            ])->handle($input);
        } catch (NotSupportedException) {
            throw new NotSupportedException('Unable to parse HSV color from input "' . $input . '"');
        }

        if (!$color instanceof self) {
            throw new ColorDecoderException('Result must be instance of ' . self::class);
        }

        return $color;
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::colorspace()
     */
    public function colorspace(): ColorspaceInterface
    {
        return new Colorspace();
    }

    /**
     * Return the Hue channel.
     */
    public function hue(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Hue::class);
    }

    /**
     * Return the Saturation channel.
     */
    public function saturation(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Saturation::class);
    }

    /**
     * Return the Value channel.
     */
    public function value(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Value::class);
    }

    /**
     * Return alpha channel.
     */
    public function alpha(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Alpha::class);
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::toHex()
     *
     * @throws InvalidArgumentException
     * @throws NotSupportedException
     */
    public function toHex(bool $prefix = false): string
    {
        return $this->toColorspace(Rgb::class)->toHex($prefix);
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::toString()
     */
    public function toString(): string
    {
        if ($this->isTransparent()) {
            return sprintf(
                'hsv(%d %d%% %d%% / %s)',
                $this->hue()->value(),
                $this->saturation()->value(),
                $this->value()->value(),
                $this->alpha()->toString(),
            );
        }

        return sprintf(
            'hsv(%d %d%% %d%%)',
            $this->hue()->value(),
            $this->saturation()->value(),
            $this->value()->value()
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::isGrayscale()
     */
    public function isGrayscale(): bool
    {
        return $this->saturation()->value() == 0;
    }
}
