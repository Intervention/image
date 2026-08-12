<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use ArrayIterator;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\PaletteInterface;
use IteratorAggregate;
use Traversable;

class Histogram implements IteratorAggregate
{
    /**
     * Histogram data.
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
     * Create histogram from array of colors.
     *
     * @param array<ColorInterface> $colors
     */
    public static function fromColors(array $colors): self
    {
        $histogram = new self();

        foreach ($colors as $color) {
            $histogram->addColor($color);
        }

        return $histogram;
    }

    /**
     * {@inheritdoc}
     *
     * @see IteratorAggregate::getIterator()
     *
     * @return Traversable<Bin>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->bins);
    }

    /**
     * Count total color population.
     */
    public function totalCount(): int
    {
        $totalPopulation = 0;
        foreach ($this as $bin) {
            $totalPopulation += $bin->count;
        }

        return $totalPopulation;
    }

    /**
     * Add color to histogram and increase count by given increment.
     */
    public function addColor(ColorInterface $color, int $increment = 1): void
    {
        $hash = $this->buildColorHash($color);

        if (!array_key_exists($hash, $this->bins)) {
            $this->bins[$hash] = new Bin($color);
        }

        $this->bins[$hash]->increaseCount($increment);
    }

    /**
     * Transform histogram to color palette.
     */
    public function toPalette(): PaletteInterface
    {
        $colors = $this->bins;

        // sort by count desc
        uasort($colors, fn(Bin $a, Bin $b): int => $b->count <=> $a->count);

        return new Palette(array_map(
            fn(Bin $bin): ColorInterface => $bin->color,
            array_values($this->bins),
        ));
    }

    public function slice(int $offset = 0, ?int $length = null): self
    {
        $this->bins = array_slice($this->bins, $offset, $length);

        return $this;
    }

    /**
     * Build hash from given color.
     */
    private function buildColorHash(ColorInterface $color): string
    {
        $channelValues = array_map(
            fn(ColorChannelInterface $channel): int|float => $channel->value(),
            $color->channels(),
        );

        return md5(implode(',', $channelValues));
    }
}
