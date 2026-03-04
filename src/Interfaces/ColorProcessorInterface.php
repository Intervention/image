<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface ColorProcessorInterface
{
    /**
     * Turn given color in the driver's color implementation.
     */
    public function colorToNative(ColorInterface $color): mixed; // todo: maybe rename to import

    /**
     * Turn the given driver's definition of a color into a color object.
     */
    public function nativeToColor(mixed $native): ColorInterface; // todo: maybe rename to export

    /**
     * Return the colorspace the processor currently operates in.
     */
    public function colorspace(): ColorspaceInterface;
}
