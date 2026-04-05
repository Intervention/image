<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Oklch;

use Intervention\Image\Colors\AbstractColor;
use Intervention\Image\Colors\Oklch\Channels\Alpha;
use Intervention\Image\Colors\Oklch\Channels\Chroma;
use Intervention\Image\Colors\Oklch\Channels\Hue;
use Intervention\Image\Colors\Oklch\Channels\Lightness;
use Intervention\Image\Colors\Oklch\Decoders\StringColorDecoder;
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
    public function __construct(float|Lightness $l, float|Chroma $c, float|Hue $h, float|Alpha $a = 1)
    {
        $this->channels = [
            is_float($l) ? new Lightness($l) : $l,
            is_float($c) ? new Chroma($c) : $c,
            is_float($h) ? new Hue($h) : $h,
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
    public static function create(float|Lightness $l, float|Chroma $c, float|Hue $h, float|Alpha $a = 1): self
    {
        return new self($l, $c, $h, $a);
    }

    /**
     * Parse OKLCH color from string.
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
                'Unable to parse OKLCH color from input "' . $input . '"',
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
     * Return the Lightness channel.
     */
    public function lightness(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Lightness::class);
    }

    /**
     * Return the chroma channel.
     */
    public function chroma(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Chroma::class);
    }

    /**
     * Return the hue channel.
     */
    public function hue(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Hue::class);
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
                'oklch(%s %s %s / %s)',
                $this->lightness()->value(),
                $this->chroma()->value(),
                $this->hue()->value(),
                $this->alpha()->toString(),
            );
        }

        return sprintf(
            'oklch(%s %s %s)',
            $this->lightness()->value(),
            $this->chroma()->value(),
            $this->hue()->value(),
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::isGrayscale()
     */
    public function isGrayscale(): bool
    {
        return $this->chroma()->value() === 0.0;
    }
}
