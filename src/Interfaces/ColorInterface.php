<?php

namespace Intervention\Image\Interfaces;

use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Rgb\Color as RgbColor;

interface ColorInterface
{
    public function toRgb(): RgbColor;
    public function toCmyk(): CmykColor;
    public function toArray(): array;
    public function toString(): string;
    public function __toString(): string;

    // public function channels(): array;
    // public function channel(string $classname): ColorChannelInterface;
    // public function colorspace(): ColorspaceInterface;
    // public function convertTo(string|ColorspaceInterface $colorspace): ColorInterface;
}
