<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Swatches;

use Countable;
use Intervention\Image\Colors\Palette;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\PaletteInterface;
use Intervention\Image\Interfaces\SwatchesInterface;
use IteratorAggregate;
use ReflectionClass;
use ReflectionProperty;
use Traversable;

/**
 * @implements IteratorAggregate<ColorInterface>
 */
abstract class AbstractSwatches implements SwatchesInterface, Countable, IteratorAggregate
{
    /**
     * {@inheritdoc}
     *
     * @see SwatchesInterface::toPalette()
     */
    public function toPalette(): PaletteInterface
    {
        return new Palette($this->toArray());
    }

    /**
     * {@inheritdoc}
     *
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator(): Traversable
    {
        $publicColorProperties = [];
        $reflection = new ReflectionClass($this);
        $publicColorProperties = array_filter(
            $reflection->getProperties(ReflectionProperty::IS_PUBLIC),
            // @phpstan-ignore method.notFound
            fn(ReflectionProperty $property): bool => $property->getType()?->getName() === ColorInterface::class,
        );

        foreach ($publicColorProperties as $property) {
            yield $this->{$property->getName()};
        }
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
     * @see PaletteInterface::toArray()
     *
     * @return array<ColorInterface>
     */
    public function toArray(): array
    {
        return iterator_to_array($this->getIterator());
    }
}
