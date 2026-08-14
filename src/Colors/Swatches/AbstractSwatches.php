<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Swatches;

use ArrayIterator;
use Countable;
use Intervention\Image\Colors\Palette;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Interfaces\PaletteInterface;
use Intervention\Image\Interfaces\SwatchesInterface;
use IteratorAggregate;
use ReflectionClass;
use ReflectionProperty;
use Traversable;

abstract class AbstractSwatches implements SwatchesInterface
{
    /**
     * {@inheritdoc}
     *
     * @see SwatchesInterface::toColorspace()
     */
    public function toColorspace(string|ColorspaceInterface $colorspace): SwatchesInterface
    {
        foreach ($this->swatchNames() as $name) {
            if ($this->{$name} !== null) {
                $this->{$name} = $this->{$name}->toColorspace($colorspace);
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see SwatchesInterface::toPalette()
     */
    public function toPalette(): PaletteInterface
    {
        return new Palette(array_filter(
            $this->toArray(),
            fn(null|ColorInterface $color): bool => $color !== null,
        ));
    }

    /**
     * {@inheritdoc}
     *
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists(mixed $offset): bool
    {
        return property_exists($this, $offset);
    }

    /**
     * {@inheritdoc}
     *
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->{$offset};
    }

    /**
     * {@inheritdoc}
     *
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->{$offset} = $value;
    }

    /**
     * {@inheritdoc}
     *
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset(mixed $offset): void
    {
        // assign null instead of unset() which would leave the
        // typed property in uninitialized state
        $this->{$offset} = null;
    }

    /**
     * {@inheritdoc}
     *
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator(): Traversable
    {
        $colors = [];
        foreach ($this->swatchNames() as $name) {
            $colors[$name] = $this->{$name};
        }

        return new ArrayIterator($colors);
    }

    /**
     * {@inheritdoc}
     *
     * @see Countable::count()
     */
    public function count(): int
    {
        return count($this->toArray());
    }

    /**
     * {@inheritdoc}
     *
     * @see SwatchesInterface::toArray()
     *
     * @return array<string, null|ColorInterface>
     */
    public function toArray(): array
    {
        return iterator_to_array($this->getIterator());
    }

    /**
     * Return array of the names of the swatches.
     *
     * @return array<string>
     */
    private function swatchNames(): array
    {
        $properties = array_filter(
            (new ReflectionClass($this))->getProperties(ReflectionProperty::IS_PUBLIC),
            // @phpstan-ignore method.notFound
            fn(ReflectionProperty $property): bool => $property->getType()?->getName() === ColorInterface::class,
        );

        return array_map(
            fn(ReflectionProperty $property): string => $property->getName(),
            $properties,
        );
    }
}
