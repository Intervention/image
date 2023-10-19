<?php

namespace Intervention\Image\Interfaces;

use Intervention\Image\Colors\Rgb\Color;

interface ColorInterface
{
    public function toRgb(): Color;
    public function toArray(): array;
    public function toString(): string;
    public function toHex(): string;
    public function __toString(): string;
    public function channels(): array;
    public function convertTo(string|ColorspaceInterface $colorspace): ColorInterface;
    public function isGreyscale(): bool;
}
