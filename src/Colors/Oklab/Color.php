<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Oklab;

use Intervention\Image\Colors\AbstractColor;
use Intervention\Image\Colors\Oklab\Channels\A;
use Intervention\Image\Colors\Oklab\Channels\B;
use Intervention\Image\Colors\Oklab\Channels\Alpha;
use Intervention\Image\Colors\Oklab\Channels\Lightness;
use Intervention\Image\Colors\Oklab\Decoders\StringColorDecoder;
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
    public function __construct(float|Lightness $l, float|A $a, float|B $b, float|Alpha $alpha = 1)
    {
        $this->channels = [
            is_float($l) ? new Lightness($l) : $l,
            is_float($a) ? new A($a) : $a,
            is_float($b) ? new B($b) : $b,
            is_float($alpha) ? new Alpha($alpha) : $alpha,
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
    public static function create(float|Lightness $l, float|A $a, float|B $b, float|Alpha $alpha = 1): self
    {
        return new self($l, $a, $b, $alpha);
    }

    /**
     * Parse OKLAB color from string.
     */
    public static function parse(string $input): self
    {
        try {
            $color = InputHandler::usingDecoders([
                StringColorDecoder::class,
            ])->handle($input);
        } catch (NotSupportedException) {
            throw new NotSupportedException('Unable to parse OKLAB color from input "' . $input . '"');
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
     * Return the Lightness channel.
     */
    public function lightness(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Lightness::class);
    }

    /**
     * Return the a axis (green-red) channel.
     */
    public function a(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(A::class);
    }

    /**
     * Return the b axis (blue-yellow) channel.
     */
    public function b(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(B::class);
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
                'oklab(%s %s %s / %s)',
                $this->lightness()->value(),
                $this->a()->value(),
                $this->b()->value(),
                $this->alpha()->toString(),
            );
        }

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
     * @see ColorInterface::isGrayscale()
     */
    public function isGrayscale(): bool
    {
        return $this->a()->value() === 0.0 && $this->b()->value() === 0.0;
    }
}
