<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Hsl;

use Intervention\Image\Colors\AbstractColor;
use Intervention\Image\Colors\Hsl\Channels\Alpha;
use Intervention\Image\Colors\Hsl\Channels\Hue;
use Intervention\Image\Colors\Hsl\Channels\Luminance;
use Intervention\Image\Colors\Hsl\Channels\Saturation;
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
    public function __construct(int $h, int $s, int $l, float $a = 1)
    {
        /** @throws void */
        $this->channels = [
            new Hue($h),
            new Saturation($s),
            new Luminance($l),
            new Alpha($a),
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
    public static function create(mixed ...$input): self
    {
        $input = match (count($input)) {
            1 => $input[0],
            3, 4 => $input,
            default => throw new InvalidArgumentException(
                'Too few arguments to create HSL color, ' . count($input) . ' passed and 1, 3 or 4 expected',
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
            throw new ColorDecoderException('Failed to decode HSL color from string "' . $input . '"');
        }

        if (!($color instanceof self)) {
            throw new ColorDecoderException(
                'Failed to decode HSL color from string "' . $input . '"',
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
     * @see ColorInterface::isGreyscale()
     */
    public function isGreyscale(): bool
    {
        return $this->saturation()->value() == 0;
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
