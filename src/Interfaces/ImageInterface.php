<?php

namespace Intervention\Image\Interfaces;

use Intervention\Image\Collection;
use Intervention\Image\EncodedImage;

interface ImageInterface
{
    public function getSize(): SizeInterface;
    public function width(): int;
    public function height(): int;
    public function isAnimated(): bool;
    public function greyscale(): ImageInterface;
    public function encode(EncoderInterface $encoder): EncodedImage;
    public function setLoops(int $count): ImageInterface;
    public function loops(): int;
    public function pickColor(int $x, int $y, int $frame_key = 0): ?ColorInterface;
    public function pickColors(int $x, int $y): Collection;
}
