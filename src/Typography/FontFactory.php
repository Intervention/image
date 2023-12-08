<?php

namespace Intervention\Image\Typography;

use Intervention\Image\Interfaces\FontInterface;

class FontFactory
{
    protected FontInterface $font;

    public function __construct(callable|FontInterface $init)
    {
        $this->font = is_a($init, FontInterface::class) ? $init : new Font();

        if (is_callable($init)) {
            $init($this);
        }
    }

    public function __invoke(): FontInterface
    {
        return $this->font;
    }

    public function filename(string $value): self
    {
        $this->font->setFilename($value);

        return $this;
    }

    public function color(mixed $value): self
    {
        $this->font->setColor($value);

        return $this;
    }

    public function size(float $value): self
    {
        $this->font->setSize($value);

        return $this;
    }

    public function align(string $value): self
    {
        $this->font->setAlignment($value);

        return $this;
    }

    public function valign(string $value): self
    {
        $this->font->setValignment($value);

        return $this;
    }

    public function lineHeight(float $value): self
    {
        $this->font->setLineHeight($value);

        return $this;
    }

    public function angle(float $value): self
    {
        $this->font->setAngle($value);

        return $this;
    }
}
