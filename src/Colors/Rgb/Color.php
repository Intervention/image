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
use Intervention\Image\InputHandler;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Interfaces\ColorInterface;
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
    public static function create(mixed ...$input): self
    {
        $input = match (count($input)) {
            1 => $input[0],
            3, 4 => $input,
            default => throw new InvalidArgumentException(
                'Too few arguments to create RGB color, ' . count($input) . ' passed and 1, 3, or 4 expected',
            ),
        };

        if (is_array($input)) {
            return new self(...$input);
        }

        try {
            $color = InputHandler::usingDecoders([
                Decoders\HexColorDecoder::class,
                Decoders\StringColorDecoder::class,
                Decoders\TransparentColorDecoder::class,
                Decoders\HtmlColornameDecoder::class,
            ])->handle($input);
        } catch (NotSupportedException) {
            throw new ColorDecoderException(
                'Failed to decode RGB color from string "' . $input . '"',
            );
        }

        if (!$color instanceof self) {
            throw new ColorDecoderException(
                'Failed to decode RGB color from string "' . $input . '"',
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

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::withTransparency()
     */
    public function withTransparency(float $transparency): ColorInterface
    {
        $color = clone $this;

        $color->channels = array_map(
            fn(ColorChannelInterface $channel): ColorChannelInterface =>
            $channel instanceof Alpha ? Alpha::fromNormalized($transparency) : $channel,
            $this->channels
        );

        return $color;
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::withBrightnessDelta()
     */
    public function withBrightnessDelta(int $percent): self
    {
        $color = clone $this;

        $color->channels = array_map(
            function (ColorChannelInterface $channel) use ($percent): ColorChannelInterface {
                return $channel instanceof Alpha ? $channel : $channel->scale($percent);
            },
            $color->channels
        );

        return $color;
    }
}
