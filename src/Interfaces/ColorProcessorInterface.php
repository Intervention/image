<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface ColorProcessorInterface
{
    /**
     * Transform the given color object into the driver's color represenation.
     */
    public function export(ColorInterface $color): mixed;

    /**
     * Transform the given driver's represenation of a color into a color object.
     */
    public function import(mixed $color): ColorInterface;

    /**
     * Return the colorspace the processor currently operates in.
     */
    public function colorspace(): ColorspaceInterface;
}
