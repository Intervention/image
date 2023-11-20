<?php

namespace Intervention\Image\Interfaces;

use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Interfaces\ColorInterface;

interface FontInterface
{
    public function setColor($color): self;
    public function color(): ColorInterface;
    public function setSize(float $size): self;
    public function size(): float;
    public function setAngle(float $angle): self;
    public function angle(): float;
    public function setFilename(string $filename): self;
    public function filename(): ?string;
    public function hasFilename(): bool;
    public function setAlignment(string $align): self;
    public function alignment(): string;
    public function setValignment(string $align): self;
    public function valignment(): string;
    public function setLineHeight(float $value): self;
    public function lineHeight(): float;
    public function leadingInPixels(): int;
    public function fontSizeInPixels(): int;
    public function capHeight(): int;
    public function getBoxSize(string $text): Polygon;
}
