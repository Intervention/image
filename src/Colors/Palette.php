<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use ArrayAccess;
use ArrayIterator;
use Countable;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Interfaces\PaletteInterface;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<ColorInterface>
 * @implements ArrayAccess<int, ColorInterface>
 */
class Palette implements PaletteInterface, Countable, IteratorAggregate, ArrayAccess
{
    /**
     * Create new instance.
     *
     * @param array<ColorInterface> $colors
     */
    public function __construct(public array $colors)
    {
        //
    }

    /**
     * {@inheritdoc}
     *
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->colors);
    }

    /**
     * {@inheritdoc}
     *
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet(mixed $offset): ColorInterface
    {
        return $this->colors[$offset];
    }

    /**
     * {@inheritdoc}
     *
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->colors[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     *
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->colors[$offset]);
    }

    /**
     * Implementation of IteratorAggregate.
     *
     * @return Traversable<ColorInterface>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->colors);
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::first()
     */
    public function first(): ?ColorInterface
    {
        if ($this->colors === []) {
            return null;
        }

        $key = array_key_first($this->colors);

        return $this->colors[$key];
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::last()
     */
    public function last(): ?ColorInterface
    {
        if ($this->colors === []) {
            return null;
        }

        $key = array_key_last($this->colors);

        return $this->colors[$key];
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::count()
     */
    public function count(): int
    {
        return count($this->colors);
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::toColorspace()
     */
    public function toColorspace(string|ColorspaceInterface $colorspace): PaletteInterface
    {
        $this->colors = array_map(
            fn(ColorInterface $color): ColorInterface => $color->toColorspace($colorspace),
            $this->colors,
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::toArray()
     */
    public function toArray(): array
    {
        return $this->colors;
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::sortByChannel()
     *
     * @throws InvalidArgumentException
     */
    public function sortByChannel(string|ColorChannelInterface $channel): PaletteInterface
    {
        if ($this->colors === []) {
            return $this;
        }

        // normalize to channel classname
        $channel = is_string($channel) ? $channel : $channel::class;
        if (!class_exists($channel)) {
            throw new InvalidArgumentException(
                'The channel class "' . $channel . '" does not exist',
            );
        }

        $originalColorspace = $this->first()->colorspace()::class;
        $sortColorspace = match ($channel) {
            Rgb\Channels\Red::class,
            Rgb\Channels\Green::class,
            Rgb\Channels\Blue::class,
            Rgb\Channels\Alpha::class => Rgb\Colorspace::class,
            Cmyk\Channels\Cyan::class,
            Cmyk\Channels\Magenta::class,
            Cmyk\Channels\Yellow::class,
            Cmyk\Channels\Key::class,
            Cmyk\Channels\Alpha::class => Cmyk\Colorspace::class,
            Hsl\Channels\Hue::class,
            Hsl\Channels\Saturation::class,
            Hsl\Channels\Luminance::class,
            Hsl\Channels\Alpha::class => Hsl\Colorspace::class,
            Hsv\Channels\Hue::class,
            Hsv\Channels\Saturation::class,
            Hsv\Channels\Value::class,
            Hsv\Channels\Alpha::class => Hsv\Colorspace::class,
            Oklab\Channels\Lightness::class,
            Oklab\Channels\A::class,
            Oklab\Channels\B::class,
            Oklab\Channels\Alpha::class => Oklab\Colorspace::class,
            Oklch\Channels\Lightness::class,
            Oklch\Channels\Chroma::class,
            Oklch\Channels\Hue::class,
            Oklch\Channels\Alpha::class => Oklch\Colorspace::class,
            default => throw new InvalidArgumentException('Unable to sort by color channel ' . $channel),
        };

        // create indexed array to track original colors
        $originalColors = $this->colors;
        $indices = array_keys($originalColors);

        // sort indices based on channel values in the sort colorspace
        usort(
            $indices,
            function (
                int $indexA,
                int $indexB,
            ) use (
                $channel,
                $sortColorspace,
                $originalColorspace,
                $originalColors,
            ): int {
                $colorA = $originalColors[$indexA];
                $colorB = $originalColors[$indexB];

                if ($sortColorspace !== $originalColorspace) {
                    $colorA = $colorA->toColorspace($sortColorspace);
                    $colorB = $colorB->toColorspace($sortColorspace);
                }

                return $colorA->channel($channel)->value() <=> $colorB->channel($channel)->value();
            },
        );

        // reorder colors based on sorted indices
        $this->colors = array_map(
            fn(int $index): ColorInterface => $originalColors[$index],
            $indices,
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::sortByChannelDesc()
     *
     * @throws InvalidArgumentException
     */
    public function sortByChannelDesc(string|ColorChannelInterface $channel): PaletteInterface
    {
        // @phpstan-ignore property.notFound
        $this->colors = array_reverse($this->sortByChannel($channel)->colors);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::slice()
     */
    public function slice(int $offset, ?int $length = null): PaletteInterface
    {
        $this->colors = array_slice($this->colors, $offset, $length);

        return $this;
    }
}
