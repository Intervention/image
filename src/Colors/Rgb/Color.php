<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Rgb;

use Intervention\Image\Colors\AbstractColor;
use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Colors\Rgb\Channels\Alpha;
use Intervention\Image\Drivers\AbstractInputHandler;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;

class Color extends AbstractColor
{
    /**
     * Create new instance
     *
     * @param int $r
     * @param int $g
     * @param int $b
     * @param int $a
     * @return ColorInterface
     */
    public function __construct(int $r, int $g, int $b, int $a = 255)
    {
        /** @throws void */
        $this->channels = [
            new Red($r),
            new Green($g),
            new Blue($b),
            new Alpha($a),
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
    public static function create(mixed $input): ColorInterface
    {
        return (new class ([
            Decoders\HexColorDecoder::class,
            Decoders\StringColorDecoder::class,
            Decoders\TransparentColorDecoder::class,
            Decoders\HtmlColornameDecoder::class,
        ]) extends AbstractInputHandler
        {
        })->handle($input);
    }

    /**
     * Return the RGB red color channel
     *
     * @return ColorChannelInterface
     */
    public function red(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Red::class);
    }

    /**
     * Return the RGB green color channel
     *
     * @return ColorChannelInterface
     */
    public function green(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Green::class);
    }

    /**
     * Return the RGB blue color channel
     *
     * @return ColorChannelInterface
     */
    public function blue(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Blue::class);
    }

    /**
     * Return the colors alpha channel
     *
     * @return ColorChannelInterface
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
                'rgba(%d, %d, %d, %.1F)',
                $this->red()->value(),
                $this->green()->value(),
                $this->blue()->value(),
                $this->alpha()->normalize(),
            );
        }

        return sprintf(
            'rgb(%d, %d, %d)',
            $this->red()->value(),
            $this->green()->value(),
            $this->blue()->value()
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::isGreyscale()
     */
    public function isGreyscale(): bool
    {
        $values = [$this->red()->value(), $this->green()->value(), $this->blue()->value()];

        return count(array_unique($values, SORT_REGULAR)) === 1;
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
}
