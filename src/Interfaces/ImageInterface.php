<?php

namespace Intervention\Image\Interfaces;

interface ImageInterface
{
    public function size(): SizeInterface;
    public function width(): int;
    public function height(): int;
    public function isAnimated(): bool;
    public function greyscale(): ImageInterface;
}
