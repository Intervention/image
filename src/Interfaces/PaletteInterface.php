<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Traversable;

/**
 * @extends Traversable<int|string, mixed>
 */
interface PaletteInterface extends Traversable
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
     * Return number of colors in palette.
     */
    public function count(): int;

    /**
     * Transform colors of palette to given color space.
     */
    public function toColorspace(string|ColorspaceInterface $colorspace): self;

    /**
     * Transform collection as array.
     *
     * @return array<ColorInterface>
     */
    public function toArray(): array;

    /**
     * Convert the palette to a version sorted by given color channel.
     */
    public function sortByChannel(string|ColorChannelInterface $channel): self;

    /**
     * Convert the palette to a version sorted by given color channel in reverse order.
     */
    public function sortByChannelDesc(string|ColorChannelInterface $channel): self;

    /**
     * ...
     *
     * @return self<int, ColorInterface>
     */
    public function slice(int $offset, ?int $length = null): self;
}
