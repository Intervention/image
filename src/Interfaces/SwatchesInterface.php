<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * @extends Traversable<string, null|ColorInterface>
 * @extends IteratorAggregate<string, null|ColorInterface>
 * @extends ArrayAccess<string, null|ColorInterface>
 */
interface SwatchesInterface extends Traversable, Countable, IteratorAggregate, ArrayAccess
{
    /**
     * Create an analyzer instance to extract colors from the image for the swatches filter.
     */
    public function colorAnalyzer(): AnalyzerInterface;

    /**
     * Create a filter instance that can categorize the extracted colors to swatches.
     */
    public function colorFilter(): ColorFilterInterface;

    /**
     * Transform all swatches to given color space.
     */
    public function toColorspace(string|ColorspaceInterface $colorspace): self;

    /**
     * Transform swatches to palette
     */
    public function toPalette(): PaletteInterface;

    /**
     * Transform swatches to array.
     *
     * @return array<string, null|ColorInterface>
     */
    public function toArray(): array;
}
