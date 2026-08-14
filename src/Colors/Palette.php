<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use ArrayAccess;
use ArrayIterator;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Interfaces\PaletteInterface;
use Intervention\Image\Traits\CanHashColor;
use Traversable;

class Palette implements PaletteInterface
{
    use CanHashColor;

    /**
     * Color data.
     *
     * @var array<string, Bin> $bins
     */
    protected array $bins = [];

    /**
     * Create new instance.
     *
     * @param array<ColorInterface> $colors
     */
    public function __construct(array $colors = [])
    {
        foreach ($colors as $color) {
            $this->addColor($color);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->toArray());
    }

    /**
     * {@inheritdoc}
     *
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet(mixed $offset): ColorInterface
    {
        return $this->toArray()[$offset];
    }

    /**
     * {@inheritdoc}
     *
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->toArray()[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     *
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->toArray()[$offset]);
    }

    /**
     * Implementation of IteratorAggregate.
     *
     * @return Traversable<ColorInterface>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->toArray());
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::addColor()
     */
    public function addColor(ColorInterface $color, int $amount = 1): self
    {
        $hash = $this->hashColor($color);
        if (!array_key_exists($hash, $this->bins)) {
            $this->bins[$hash] = new Bin($color);
        }

        $this->bins[$hash]->increaseCount($amount);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::first()
     */
    public function first(): ?ColorInterface
    {
        if ($this->bins === []) {
            return null;
        }

        return $this->bins[array_key_first($this->bins)]->color;
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::last()
     */
    public function last(): ?ColorInterface
    {
        if ($this->bins === []) {
            return null;
        }

        return $this->bins[array_key_last($this->bins)]->color;
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::count()
     */
    public function count(): int
    {
        return count($this->bins);
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::colorCount()
     */
    public function colorCount(ColorInterface $color): int
    {
        return $this->bins[$this->hashColor($color)]->count;
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::totalCount()
     */
    public function totalCount(): int
    {
        return array_sum(array_map(fn(Bin $bin): int => $bin->count, $this->bins));
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::toColorspace()
     */
    public function toColorspace(string|ColorspaceInterface $colorspace): PaletteInterface
    {
        array_walk($this->bins, function (Bin $bin) use ($colorspace): void {
            $bin->color = $bin->color->toColorspace($colorspace);
        });

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::toArray()
     */
    public function toArray(): array
    {
        return array_map(
            fn(Bin $bin): ColorInterface => $bin->color,
            array_values($this->bins),
        );
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
        if ($this->bins === []) {
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
        $originalBins = $this->bins;
        $indices = array_keys($originalBins);

        // sort indices based on channel values in the sort colorspace
        usort(
            $indices,
            function (
                string $indexA,
                string $indexB,
            ) use (
                $channel,
                $sortColorspace,
                $originalColorspace,
                $originalBins,
            ): int {
                $colorA = $originalBins[$indexA]->color;
                $colorB = $originalBins[$indexB]->color;

                if ($sortColorspace !== $originalColorspace) {
                    $colorA = $colorA->toColorspace($sortColorspace);
                    $colorB = $colorB->toColorspace($sortColorspace);
                }

                return $colorA->channel($channel)->value() <=> $colorB->channel($channel)->value();
            },
        );

        // reorder colors based on sorted indices and preserve keys
        // @phpstan-ignore missingType.checkedException
        $this->bins = array_combine(
            $indices,
            array_map(fn(string $index): Bin => $originalBins[$index], $indices),
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
        $this->sortByChannel($channel);

        $this->bins = array_reverse($this->bins, preserve_keys: true);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::sortByPresence()
     */
    public function sortByPresence(): PaletteInterface
    {
        uasort($this->bins, fn(Bin $binA, Bin $binB): int => $binA->count <=> $binB->count);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::sortByPresenceDesc()
     */
    public function sortByPresenceDesc(): PaletteInterface
    {
        $this->sortByPresence();

        $this->bins = array_reverse($this->bins, preserve_keys: true);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::slice()
     */
    public function slice(int $offset, ?int $length = null): PaletteInterface
    {
        $this->bins = array_slice($this->bins, $offset, $length);

        return $this;
    }
}
