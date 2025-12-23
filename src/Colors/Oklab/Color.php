<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Oklab;

use Intervention\Image\Colors\AbstractColor;
// use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
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
    public function __construct(float $l, float $a, float $b)
    {
        /** @throws void */
        $this->channels = [
            new Channels\Lightness($l),
            new Channels\A($a),
            new Channels\B($b),
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
                'Too few arguments to create OKLAB color, ' . count($input) . ' passed and 1 or 3 expected',
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
            throw new ColorDecoderException('Failed to decode OKLAB color from string "' . $input . '"');
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
     * Return the a axis (green-red) channel
     */
    public function a(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Channels\A::class);
    }

    /**
     * Return the b axis (blue-yellow) channel
     */
    public function b(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Channels\B::class);
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::toHex()
     */
    public function toHex(string $prefix = ''): string
    {
        // return $this->convertTo(RgbColorspace::class)->toHex($prefix);
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::toString()
     */
    public function toString(): string
    {
        return sprintf(
            'oklab(%s %s %s)',
            $this->lightness()->value(),
            $this->a()->value(),
            $this->b()->value(),
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::isGreyscale()
     */
    public function isGreyscale(): bool
    {
        // TODO: confirm correct implementation
        return $this->a()->value() === 0.0 && $this->b()->value() === 0.0;
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
