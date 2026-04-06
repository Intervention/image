<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Hsl;

use Intervention\Image\Colors\AbstractColor;
use Intervention\Image\Colors\Hsl\Channels\Alpha;
use Intervention\Image\Colors\Hsl\Channels\Hue;
use Intervention\Image\Colors\Hsl\Channels\Luminance;
use Intervention\Image\Colors\Hsl\Channels\Saturation;
use Intervention\Image\Colors\Hsl\Decoders\StringColorDecoder;
use Intervention\Image\Colors\Rgb\Colorspace as Rgb;
use Intervention\Image\Exceptions\ColorException;
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
     *
     * @throws InvalidArgumentException
     */
    public function __construct(int|Hue $h, int|Saturation $s, int|Luminance $l, float|Alpha $a = 1)
    {
        $this->channels = [
            is_int($h) ? new Hue($h) : $h,
            is_int($s) ? new Saturation($s) : $s,
            is_int($l) ? new Luminance($l) : $l,
            is_float($a) ? new Alpha($a) : $a,
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::create()
     *
     * @throws InvalidArgumentException
     */
    public static function create(int|Hue $h, int|Saturation $s, int|Luminance $l, float|Alpha $a = 1): self
    {
        return new self($h, $s, $l, $a);
    }

    /**
     * Parse HSL color from string.
     *
     * @throws InvalidArgumentException
     * @throws ColorException
     */
    public static function parse(string $input): self
    {
        try {
            $color = InputHandler::usingDecoders([
                StringColorDecoder::class,
            ])->handle($input);
        } catch (NotSupportedException | DriverException $e) {
            throw new InvalidArgumentException(
                'Unable to parse HSL color from input "' . $input . '"',
                previous: $e,
            );
        }

        if (!$color instanceof self) {
            throw new ColorException('Result must be instance of ' . self::class);
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
     * Return the Hue channel
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
     * Return the Luminance channel.
     */
    public function luminance(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Luminance::class);
    }

    /**
     * Return the alpha channel.
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
     * @throws NotSupportedException
     */
    public function toHex(bool $prefix = false): string
    {
        // @phpstan-ignore missingType.checkedException
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
                'hsl(%d %d%% %d%% / %s)',
                $this->hue()->value(),
                $this->saturation()->value(),
                $this->luminance()->value(),
                $this->alpha()->toString(),
            );
        }

        return sprintf(
            'hsl(%d %d%% %d%%)',
            $this->hue()->value(),
            $this->saturation()->value(),
            $this->luminance()->value()
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
