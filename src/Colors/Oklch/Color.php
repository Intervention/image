<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Oklch;

use Intervention\Image\Colors\AbstractColor;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
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
    public function __construct(float $l, float $c, float $h, float $a = 1)
    {
        /** @throws void */
        $this->channels = [
            new Channels\Lightness($l),
            new Channels\Chroma($c),
            new Channels\Hue($h),
            new Channels\Alpha($a),
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::create()
     *
     * @throws InvalidArgumentException
     * @throws ColorDecoderException
     * @throws DriverException
     */
    public static function create(mixed ...$input): self
    {
        $input = match (count($input)) {
            1 => $input[0],
            3, 4 => $input,
            default => throw new InvalidArgumentException(
                'Too few arguments to create OKLCH color, ' . count($input) . ' passed and 1, 3 or 4 expected',
            ),
        };

        if (is_array($input)) {
            return new self(...$input);
        }

        try {
            $color = InputHandler::withDecoders([
                Decoders\StringColorDecoder::class,
            ])->handle($input);
        } catch (NotSupportedException) {
            throw new ColorDecoderException('Failed to decode OKLCH color from string "' . $input . '"');
        }

        if (!($color instanceof self)) {
            throw new ColorDecoderException(
                'Failed to decode OKLCH color from string "' . $input . '"',
            );
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
        return $this->channel(Channels\Lightness::class);
    }

    /**
     * Return the chroma channel.
     */
    public function chroma(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Channels\Chroma::class);
    }

    /**
     * Return the hue channel.
     */
    public function hue(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Channels\Hue::class);
    }

    /**
     * Return the alpha channel.
     */
    public function alpha(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Channels\Alpha::class);
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::toHex()
     *
     * @throws InvalidArgumentException
     * @throws NotSupportedException
     */
    public function toHex(string $prefix = ''): string
    {
        if (!in_array($prefix, ['', '#'])) {
            throw new InvalidArgumentException(
                'Hexadecimal color prefix must be "#" or empty string',
            );
        }

        return $this->toColorspace(RgbColorspace::class)->toHex($prefix);
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
                round($this->alpha()->value(), 2)
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

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::isTransparent()
     */
    public function isTransparent(): bool
    {
        return $this->alpha()->value() < $this->alpha()->max();
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::isClear()
     */
    public function isClear(): bool
    {
        return $this->alpha()->value() == 0;
    }
}
