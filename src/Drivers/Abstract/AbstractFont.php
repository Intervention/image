<?php

namespace Intervention\Image\Drivers\Abstract;

use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\FontInterface;
use Intervention\Image\Traits\CanHandleInput;

abstract class AbstractFont implements FontInterface
{
    use CanHandleInput;

    protected $size = 12;
    protected $angle = 0;
    protected $color = '000000';
    protected $filename;
    protected $align = 'left';
    protected $valign = 'bottom';
    protected $lineHeight = 1.25;

    public function size(float $size): FontInterface
    {
        $this->size = $size;

        return $this;
    }

    public function getSize(): float
    {
        return $this->size;
    }

    public function angle(float $angle): FontInterface
    {
        $this->angle = $angle;

        return $this;
    }

    public function getAngle(): float
    {
        return $this->angle;
    }

    public function filename(string $filename): FontInterface
    {
        $this->filename = $filename;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function hasFilename(): bool
    {
        return is_file($this->filename);
    }

    public function color($color): FontInterface
    {
        $this->color = $color;

        return $this;
    }

    public function getColor(): ?ColorInterface
    {
        return $this->handleInput($this->color);
    }

    public function align(string $align): FontInterface
    {
        $this->align = $align;

        return $this;
    }

    public function getValign(): string
    {
        return $this->valign;
    }

    public function valign(string $valign): FontInterface
    {
        $this->valign = $valign;

        return $this;
    }

    public function getAlign(): string
    {
        return $this->align;
    }

    public function lineHeight(float $height): FontInterface
    {
        $this->lineHeight = $height;

        return $this;
    }

    public function getLineHeight(): float
    {
        return $this->lineHeight;
    }

    public function leadingInPixels(): int
    {
        return intval(round($this->fontSizeInPixels() * $this->getLineHeight()));
    }

    public function capHeight(): int
    {
        return $this->getBoxSize('T')->getHeight();
    }

    public function fontSizeInPixels(): int
    {
        return $this->getBoxSize('Hy')->getHeight();
    }
}
