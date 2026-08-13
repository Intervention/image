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
}
