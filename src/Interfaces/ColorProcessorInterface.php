<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface ColorProcessorInterface
{
    /**
     * Turn given color in the driver's color implementation
     *
     * @param ColorInterface $color
     * @return mixed
     */
    public function colorToNative(ColorInterface $color);

    /**
     * Turn the given driver's definition of a color into a color object
     *
     * @param mixed $native
     * @return ColorInterface
     */
    public function nativeToColor(mixed $native): ColorInterface;
}
