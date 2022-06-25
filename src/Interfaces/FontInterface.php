<?php

namespace Intervention\Image\Interfaces;

use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Interfaces\ColorInterface;

interface FontInterface
{
    public function color($color): self;
    public function getColor(): ?ColorInterface;
    public function size(float $size): self;
    public function getSize(): float;
    public function angle(float $angle): self;
    public function getAngle(): float;
    public function filename(string $filename): self;
    public function getFilename(): ?string;
    public function hasFilename(): bool;
    public function align(string $align): self;
    public function getAlign(): string;
    public function valign(string $align): self;
    public function getValign(): string;
    public function lineHeight(float $value): self;
    public function getLineHeight(): float;
    public function leadingInPixels(): int;
    public function fontSizeInPixels(): int;
    public function capHeight(): int;
    public function getBoxSize(string $text): Polygon;
}
