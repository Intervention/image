<?php

namespace Intervention\Image\Interfaces;

interface DriverInterface
{
    public function resolve(object $input): object;
    public function createImage(int $width, int $height): ImageInterface;
    public function handleInput(mixed $input): ImageInterface|ColorInterface;
    public function colorToNative(ColorInterface $color, ColorspaceInterface $colorspace): mixed;
}
