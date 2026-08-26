<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use ArrayAccess;
use ArrayIterator;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\NotSupportedException;
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
     *
     * @throws NotSupportedException
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new NotSupportedException('Unable to set color via array access, use addColor() instead');
    }

    /**
     * {@inheritdoc}
     *
     * @see ArrayAccess::offsetUnset()
     *
     * @throws NotSupportedException
     */
    public function offsetUnset(mixed $offset): void
    {
        throw new NotSupportedException('Unable to remove color from palette via array access');
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
    public function addColor(ColorInterface $color, int $count = 1): self
    {
        $hash = $this->hashColor($color);
        if (!array_key_exists($hash, $this->bins)) {
            $this->bins[$hash] = new Bin($color);
        }

        $this->bins[$hash]->increaseCount($count);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::quantize()
     *
     * @throws InvalidArgumentException
     */
    public function quantize(int $levels): self
    {
        $bins = $this->bins;
        $this->bins = [];

        foreach ($bins as $bin) {
            $this->addColor($this->quantizeColor($bin->color, $levels), $bin->count);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::reduce()
     *
     * @throws InvalidArgumentException
     */
    public function reduce(int $levels): self
    {
        $groups = []; // group colors by quantized hash
        foreach ($this->bins as $bin) {
            $quantizedHash = $this->hashColor($this->quantizeColor($bin->color, $levels));
            $groups[$quantizedHash][] = $bin;
        }

        $this->bins = [];

        // select color with highest count per group
        foreach ($groups as $bins) {
            $total = 0;
            $representative = $bins[0]; // select first color as representative of group by default
            foreach ($bins as $bin) {
                $total += $bin->count;
                if ($bin->count > $representative->count) {
                    $representative = $bin; // update representative if count is higher
                }
            }

            // finally add representative of group as new color
            $this->addColor($representative->color, $total);
        }

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
        return $this->bins[$this->hashColor($color)]->count ?? 0;
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
        // rebuild bins because the keys are hashed from the colors; colors that
        // become identical after conversion are merged into one bin
        $bins = [];
        foreach ($this->bins as $bin) {
            $color = $bin->color->toColorspace($colorspace);
            $hash = $this->hashColor($color);
            if (array_key_exists($hash, $bins)) {
                $bins[$hash]->increaseCount($bin->count);
            } else {
                $bins[$hash] = new Bin($color, $bin->count);
            }
        }

        $this->bins = $bins;

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
     * @see PaletteInterface::filter()
     */
    public function filter(callable $callback): PaletteInterface
    {
        return new self(
            array_filter(
                array_map(fn(Bin $bin): ColorInterface => $bin->color, $this->bins),
                fn(ColorInterface $color) => $callback($color),
            ),
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::map()
     */
    public function map(callable $callback): PaletteInterface
    {
        return new self(
            array_map(
                fn(ColorInterface $color) => $callback($color),
                array_map(fn(Bin $bin): ColorInterface => $bin->color, $this->bins),
            ),
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

        // sort indices based on channel values in the sort colorspace; each
        // color is converted individually because palettes may mix colorspaces
        usort(
            $indices,
            function (
                string $indexA,
                string $indexB,
            ) use (
                $channel,
                $sortColorspace,
                $originalBins,
            ): int {
                $colorA = $originalBins[$indexA]->color;
                $colorB = $originalBins[$indexB]->color;

                if ($colorA->colorspace()::class !== $sortColorspace) {
                    $colorA = $colorA->toColorspace($sortColorspace);
                }

                if ($colorB->colorspace()::class !== $sortColorspace) {
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
        // sort by bin count first and hash secondary
        uksort($this->bins, function (string $hashA, string $hashB): int {
            return [$this->bins[$hashA]->count, $hashA] <=> [$this->bins[$hashB]->count, $hashB];
        });

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

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::hasColor()
     */
    public function hasColor(ColorInterface $color): bool
    {
        return array_key_exists($this->hashColor($color), $this->bins);
    }

    /**
     * Quantize given color to number of levels.
     *
     * @throws InvalidArgumentException
     */
    private function quantizeColor(ColorInterface $color, int $levels): ColorInterface
    {
        if ($levels < 1 || $levels > 256) {
            throw new InvalidArgumentException('Quantization levels value must be between 1 and 256');
        }

        // preserve alpha unquantized
        $alpha = $color->alpha()->normalized();

        // normalized channel values
        $normalized = array_map(
            fn(ColorChannelInterface $channel): float => $channel->normalized(),
            $color->channels(),
        );

        // normalized channel values to bin index
        $quantized = array_map(
            function (float $normalized) use ($levels): int {
                $bin = (int) floor($normalized * $levels); // 1.0 belongs to the last bin.
                return min($bin, $levels - 1);
            },
            $normalized,
        );

        // bin index to quantized normalized channel values
        $quantizedNormalized = array_map(
            function (int $bin) use ($levels): float {
                $bin = max(0, min($levels - 1, $bin));
                return ($bin + 0.5) / $levels;
            },
            $quantized,
        );

        // transform quantized channel values to color object
        $color = $color->colorspace()->colorFromNormalized($quantizedNormalized);

        // re-apply preserve alpha
        return $color->withTransparency($alpha);
    }
}
