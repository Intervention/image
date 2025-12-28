<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Oklch;

use Intervention\Image\Colors\AbstractColor;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Exceptions\ColorDecoderException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\InputHandler;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;

class Color extends AbstractColor
{
    /**
     * Create new color object
     *
     * @return void
     */
    public function __construct(float $l, float $c, float $h)
    {
        /** @throws void */
        $this->channels = [
            new Channels\Lightness($l),
            new Channels\Chroma($c),
            new Channels\Hue($h),
        ];
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
     * {@inheritdoc}
     *
     * @see ColorInterface::create()
     */
    public static function create(mixed ...$input): ColorInterface
    {
        $input = match (count($input)) {
            1 => $input[0],
            3 => $input,
            default => throw new InvalidArgumentException(
                'Too few arguments to create OKLCH color, ' . count($input) . ' passed and 1 or 3 expected',
            ),
        };

        if (is_array($input)) {
            return new self(...$input);
        }

        try {
            return InputHandler::withDecoders([
                Decoders\StringColorDecoder::class,
            ])->handle($input);
        } catch (NotSupportedException) {
            throw new ColorDecoderException('Failed to decode OKLCH color from string "' . $input . '"');
        }
    }

    /**
     * Return the Lightness channel
     */
    public function lightness(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Channels\Lightness::class);
    }

    /**
     * Return the chroma channel
     */
    public function chroma(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Channels\Chroma::class);
    }

    /**
     * Return the hue channel
     */
    public function hue(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Channels\Hue::class);
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::toHex()
     */
    public function toHex(string $prefix = ''): string
    {
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
            'oklch(%s %s %s)',
            $this->lightness()->value(),
            $this->chroma()->value(),
            $this->hue()->value(),
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::isGreyscale()
     */
    public function isGreyscale(): bool
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
        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::isClear()
     */
    public function isClear(): bool
    {
        return false;
    }
}
