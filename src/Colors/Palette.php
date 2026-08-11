<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use ArrayAccess;
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
