<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * @extends Traversable<int, ColorInterface>
 * @extends IteratorAggregate<int, ColorInterface>
 * @extends ArrayAccess<int, ColorInterface>
 */
interface PaletteInterface extends Traversable, Countable, IteratorAggregate, ArrayAccess
{
    /**
     * Get first color of palette.
     */
    public function first(): ?ColorInterface;

    /**
     * Get last color of palette.
     */
    public function last(): ?ColorInterface;

    /**
     * Return number of unique colors in palette.
     */
    public function count(): int;

    /**
     * Return the number of times the color appears in the palette.
     */
    public function colorCount(ColorInterface $color): int;

    /**
     * Count total sum of all color in palette.
     */
    public function totalCount(): int;

    /**
     * Transform all colors in palette to given color space.
     */
    public function toColorspace(string|ColorspaceInterface $colorspace): self;

    /**
     * Transform palette to array.
     *
     * @return array<ColorInterface>
     */
    public function toArray(): array;

    /**
     * Sort the palette by given color channel.
     */
    public function sortByChannel(string|ColorChannelInterface $channel): self;

    /**
     * Sort the palette by given color channel in reverse order.
     */
    public function sortByChannelDesc(string|ColorChannelInterface $channel): self;

    /**
     * Transform the palette by retaining only the specified number of colors after the offset.
     *
     * @return self<ColorInterface>
     */
    public function slice(int $offset, ?int $length = null): self;

    /**
     * Add color to palette.
     */
    public function addColor(ColorInterface $color, int $amount = 1): self;
}
