<?php

namespace Intervention\Image\Drivers;

use Intervention\Image\Interfaces\FontInterface;

abstract class AbstractFont implements FontInterface
{
    protected float $size = 12;
    protected float $angle = 0;
    protected mixed $color = '000000';
    protected ?string $filename = null;
    protected string $align = 'left';
    protected string $valign = 'bottom';
    protected float $lineHeight = 1.25;

    public function setSize(float $size): FontInterface
    {
        $this->size = $size;

        return $this;
    }

    public function size(): float
    {
        return $this->size;
    }

    public function setAngle(float $angle): FontInterface
    {
        $this->angle = $angle;

        return $this;
    }

    public function angle(): float
    {
        return $this->angle;
    }

    public function setFilename(string $filename): FontInterface
    {
        $this->filename = $filename;

        return $this;
    }

    public function filename(): ?string
    {
        return $this->filename;
    }

    public function hasFilename(): bool
    {
        return !is_null($this->filename) && is_file($this->filename);
    }

    public function setColor(mixed $color): FontInterface
    {
        $this->color = $color;

        return $this;
    }

    public function color(): mixed
    {
        return $this->color;
    }

    public function setAlignment(string $align): FontInterface
    {
        $this->align = $align;

        return $this;
    }

    public function valignment(): string
    {
        return $this->valign;
    }

    public function setValignment(string $valign): FontInterface
    {
        $this->valign = $valign;

        return $this;
    }

    public function alignment(): string
    {
        return $this->align;
    }

    public function setLineHeight(float $height): FontInterface
    {
        $this->lineHeight = $height;

        return $this;
    }

    public function lineHeight(): float
    {
        return $this->lineHeight;
    }

    public function leadingInPixels(): int
    {
        return intval(round($this->fontSizeInPixels() * $this->lineHeight()));
    }

    public function capHeight(): int
    {
        return $this->getBoxSize('T')->height();
    }

    public function fontSizeInPixels(): int
    {
        return $this->getBoxSize('Hy')->height();
    }
}
