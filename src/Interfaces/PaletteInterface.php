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
     * Run callback on each color in the palette and keep only the ones that return true.
     */
    public function filter(callable $callback): self;

    /**
     * Map each color of the palette by given callback.
     */
    public function map(callable $callback): self;

    /**
     * Sort the palette by given color channel.
     */
    public function sortByChannel(string|ColorChannelInterface $channel): self;

    /**
     * Sort the palette by given color channel in reverse order.
     */
    public function sortByChannelDesc(string|ColorChannelInterface $channel): self;

    /**
     * Sort the palette by the number of times each color appears in the image starting with the smallest number.
     */
    public function sortByPresence(): self;

    /**
     * Sort the palette by the number of times each color appears in the image in reverse order.
     */
    public function sortByPresenceDesc(): self;

    /**
     * Transform the palette by retaining only the specified number of colors after the offset.
     */
    public function slice(int $offset, ?int $length = null): self;

    /**
     * Add color to palette.
     */
    public function addColor(ColorInterface $color, int $count = 1): self;

    /**
     * Determine if given color exists in the palette.
     */
    public function hasColor(ColorInterface $color): bool;

    /**
     * Combine similar colors in the palette by replacing them to their quantized
     * version according to the given level of quantization.
     */
    public function quantize(int $levels): self;

    /**
     * Reduce similar colors in the palette by quantization with the given levels of detail
     * but keep nearest original color instead of its quantized version.
     */
    public function reduce(int $levels): self;
}
