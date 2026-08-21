<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Themes;

use Intervention\Image\Colors\Palette;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\PaletteInterface;
use Intervention\Image\Interfaces\ThemeInterface;
use ReflectionClass;
use ReflectionProperty;

abstract class AbstractTheme implements ThemeInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ThemeInterface::toPalette()
     */
    public function toPalette(): PaletteInterface
    {
        $colors = array_map(
            fn(string $name): ?ColorInterface => property_exists($this, $name) ? $this->{$name} : null,
            $this->swatchNames(),
        );

        return new Palette(array_filter(
            $colors,
            fn(null|ColorInterface $color): bool => $color !== null,
        ));
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
