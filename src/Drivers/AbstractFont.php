<?php

namespace Intervention\Image\Drivers;

use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\FontInterface;
use Intervention\Image\Traits\CanCheckType;
use Intervention\Image\Traits\CanHandleInput;

abstract class AbstractFont implements FontInterface
{
    use CanHandleInput;
    use CanCheckType;

    protected $size = 12;
    protected $angle = 0;
    protected $color = '000000';
    protected $filename;
    protected $align = 'left';
    protected $valign = 'bottom';
    protected $lineHeight = 1.25;

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

    public function setColor($color): FontInterface
    {
        $this->color = $color;

        return $this;
    }

    public function color(): ColorInterface
    {
        return $this->handleInput($this->color);
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
