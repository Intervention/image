<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use ArrayIterator;
use Countable;
use Intervention\Image\Exceptions\PaletteException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Interfaces\PaletteInterface;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<ColorInterface>
 */
class Palette implements PaletteInterface, Countable, IteratorAggregate
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
     *
     * @throws PaletteException
     */
    public function first(): ?ColorInterface
    {
        $colors = $this->colors;

        return reset($colors);
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::last()
     *
     * @throws PaletteException
     */
    public function last(): ?ColorInterface
    {
        $colors = $this->colors;

        if (count($colors) === 0) {
            return null;
        }

        return end($colors);
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::count()
     *
     * @throws PaletteException
     */
    public function count(): int
    {
        return count($this->colors);
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::toColorspace()
     *
     * @throws PaletteException
     */
    public function toColorspace(string|ColorspaceInterface $colorspace): PaletteInterface
    {
        $palette = clone $this;

        $palette->colors = array_map(
            fn(ColorInterface $color): ColorInterface => $color->toColorspace($colorspace),
            $palette->colors,
        );

        return $palette;
    }

    /**
     * {@inheritdoc}
     *
     * @see PaletteInterface::toArray()
     *
     * @throws PaletteException
     */
    public function toArray(): array
    {
        return $this->colors;
    }
}
