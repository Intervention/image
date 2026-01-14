<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface ColorProcessorInterface
{
    /**
     * Turn given color in the driver's color implementation.
     */
    public function colorToNative(ColorInterface $color): mixed;

    /**
     * Turn the given driver's definition of a color into a color object.
     */
    public function nativeToColor(mixed $native): ColorInterface;

    /**
     * Return the colorspace the processor currently operates in.
     */
    public function colorspace(): ColorspaceInterface;
}
