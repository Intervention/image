<?php

namespace Intervention\Image\Interfaces;

use Intervention\Image\EncodedImage;

interface ImageInterface
{
    public function size(): SizeInterface;
    public function width(): int;
    public function height(): int;
    public function isAnimated(): bool;
    public function greyscale(): ImageInterface;
    public function encode(EncoderInterface $encoder): EncodedImage;
}
