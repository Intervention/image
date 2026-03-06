<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Rgb;

use Intervention\Image\Colors\AbstractColor;
use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Colors\Rgb\Channels\Alpha;
use Intervention\Image\Exceptions\ColorDecoderException;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;

class Color extends AbstractColor
{
    /**
     * Create new instance.
     */
    public function __construct(int|Red $r, int|Green $g, int|Blue $b, float|Alpha $a = 1)
    {
        $this->channels = [
            is_int($r) ? new Red($r) : $r,
            is_int($g) ? new Green($g) : $g,
            is_int($b) ? new Blue($b) : $b,
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
    public static function create(int|Red $r, int|Green $g, int|Blue $b, float|Alpha $a = 1): self
    {
        return new self($r, $g, $b, $a);
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
     * Return the RGB red color channel.
     */
    public function red(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Red::class);
    }

    /**
     * Return the RGB green color channel.
     */
    public function green(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Green::class);
    }

    /**
     * Return the RGB blue color channel.
     */
    public function blue(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Blue::class);
    }

    /**
     * Return the colors alpha channel.
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
        if ($this->isTransparent()) {
            return sprintf(
                '%s%02x%02x%02x%02x',
                $prefix,
                $this->red()->value(),
                $this->green()->value(),
                $this->blue()->value(),
                $this->alpha()->value()
            );
        }

        return sprintf(
            '%s%02x%02x%02x',
            $prefix,
            $this->red()->value(),
            $this->green()->value(),
            $this->blue()->value()
        );
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
                'rgb(%d %d %d / %s)',
                $this->red()->value(),
                $this->green()->value(),
                $this->blue()->value(),
                $this->alpha()->toString(),
            );
        }

        return sprintf(
            'rgb(%d %d %d)',
            $this->red()->value(),
            $this->green()->value(),
            $this->blue()->value()
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::isGrayscale()
     */
    public function isGrayscale(): bool
    {
        $values = [$this->red()->value(), $this->green()->value(), $this->blue()->value()];

        return count(array_unique($values, SORT_REGULAR)) === 1;
    }
}
