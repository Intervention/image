<?php

namespace Intervention\Image\Interfaces;

interface SizeInterface
{
    public function getWidth(): int;
    public function getHeight(): int;
    public function getPivot(): PointInterface;
    public function setWidth(int $width): SizeInterface;
    public function setHeight(int $height): SizeInterface;
    public function getAspectRatio(): float;
    public function fitsInto(SizeInterface $size): bool;
    public function isLandscape(): bool;
    public function isPortrait(): bool;
}
