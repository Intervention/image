<?php

namespace Intervention\Image\Typography;

use Intervention\Image\Interfaces\FontInterface;

class Font implements FontInterface
{
    protected float $size = 12;
    protected float $angle = 0;
    protected mixed $color = '000000';
    protected ?string $filename = null;
    protected string $alignment = 'left';
    protected string $valignment = 'bottom';
    protected float $lineHeight = 1.25;

    public function __construct(?string $filename = null)
    {
        $this->filename = $filename;
    }

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

    public function alignment(): string
    {
        return $this->alignment;
    }

    public function setAlignment(string $value): FontInterface
    {
        $this->alignment = $value;

        return $this;
    }

    public function valignment(): string
    {
        return $this->valignment;
    }

    public function setValignment(string $value): FontInterface
    {
        $this->valignment = $value;

        return $this;
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
}
