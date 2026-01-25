<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Cmyk;

use Intervention\Image\Colors\AbstractColor;
use Intervention\Image\Colors\Cmyk\Channels\Alpha;
use Intervention\Image\Colors\Cmyk\Channels\Cyan;
use Intervention\Image\Colors\Cmyk\Channels\Magenta;
use Intervention\Image\Colors\Cmyk\Channels\Yellow;
use Intervention\Image\Colors\Cmyk\Channels\Key;
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
     * Create new instance.
     */
    public function __construct(int|Cyan $c, int|Magenta $m, int|Yellow $y, int|Key $k, float|Alpha $a = 1)
    {
        $this->channels = [
            is_int($c) ? new Cyan($c) : $c,
            is_int($m) ? new Magenta($m) : $m,
            is_int($y) ? new Yellow($y) : $y,
            is_int($k) ? new Key($k) : $k,
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
            4, 5 => $input,
            default => throw new InvalidArgumentException(
                'Too few arguments to create CMYK color, ' . count($input) . ' passed and 1, 4 or 5 expected',
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
            throw new ColorDecoderException(
                'Failed to decode CMYK color from string "' . $input . '"',
            );
        }

        if (!$color instanceof self) {
            throw new ColorDecoderException(
                'Failed to decode CMYK color from string "' . $input . '"',
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
     * {@inheritdoc}
     *
     * @see ColorInterface::toHex()
     *
     * @throws NotSupportedException
     */
    public function toHex(string $prefix = ''): string
    {
        return $this->toColorspace(Rgb::class)->toHex($prefix);
    }

    /**
     * Return the CMYK cyan channel.
     */
    public function cyan(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Cyan::class);
    }

    /**
     * Return the CMYK magenta channel.
     */
    public function magenta(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Magenta::class);
    }

    /**
     * Return the CMYK yellow channel.
     */
    public function yellow(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Yellow::class);
    }

    /**
     * Return the CMYK key channel.
     */
    public function key(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Key::class);
    }

    /**
     * Return the CMYK alpha channel.
     */
    public function alpha(): ColorChannelInterface
    {
        /** @throws void */
        return $this->channel(Alpha::class);
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
                'cmyk(%d %d %d %d / %s)',
                $this->cyan()->value(),
                $this->magenta()->value(),
                $this->yellow()->value(),
                $this->key()->value(),
                $this->alpha()->toString(),
            );
        }

        return sprintf(
            'cmyk(%d %d %d %d)',
            $this->cyan()->value(),
            $this->magenta()->value(),
            $this->yellow()->value(),
            $this->key()->value()
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::isGrayscale()
     */
    public function isGrayscale(): bool
    {
        return 0 === array_sum([
            $this->cyan()->value(),
            $this->magenta()->value(),
            $this->yellow()->value(),
        ]);
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
