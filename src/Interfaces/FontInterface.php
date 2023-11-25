<?php

namespace Intervention\Image\Interfaces;

interface FontInterface
{
    public function setColor(mixed $color): self;
    public function color(): mixed;
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
}
