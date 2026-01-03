<?php

declare(strict_types=1);

namespace Intervention\Image\Typography;

use Intervention\Image\Alignment;
use Intervention\Image\Interfaces\FontInterface;

class FontFactory
{
    protected FontInterface $font;

    /**
     * Create new instance.
     */
    public function __construct(null|callable|FontInterface $font = null)
    {
        $this->font = is_a($font, FontInterface::class) ? $font : new Font();

        if (is_callable($font)) {
            $font($this);
        }
    }

    /**
     * Create the end product of the factory statically by calling given callable
     */
    public static function build(null|callable|FontInterface $font = null): FontInterface
    {
        return (new self($font))->font();
    }

    /**
     * Return the end product of the factory
     */
    public function font(): FontInterface
    {
        return $this->font;
    }

    /**
     * Set the filename of the font to be built.
     */
    public function filename(string $value): self
    {
        $this->font->setFilepath($value);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see self::filename()
     */
    public function file(string $value): self
    {
        return $this->filename($value);
    }

    /**
     * {@inheritdoc}
     *
     * @see self::filename()
     */
    public function filepath(string $value): self
    {
        return $this->filename($value);
    }

    /**
     * Set outline stroke effect for the font to be built.
     */
    public function stroke(mixed $color, int $width = 1): self
    {
        $this->font->setStrokeWidth($width);
        $this->font->setStrokeColor($color);

        return $this;
    }

    /**
     * Set color for the font to be built.
     */
    public function color(mixed $value): self
    {
        $this->font->setColor($value);

        return $this;
    }

    /**
     * Set the size for the font to be built.
     */
    public function size(float $value): self
    {
        $this->font->setSize($value);

        return $this;
    }

    /**
     * Set the horizontal alignment of the font to be built.
     */
    public function align(string|Alignment $value): self
    {
        $this->font->setAlignment($value);

        return $this;
    }

    /**
     * Set the vertical alignment of the font to be built.
     */
    public function alignVertically(string|Alignment $value): self
    {
        $this->font->setVerticalAlignment($value);

        return $this;
    }

    /**
     * Set the line height of the font to be built.
     */
    public function lineHeight(float $value): self
    {
        $this->font->setLineHeight($value);

        return $this;
    }

    /**
     * Set the rotation angle of the font to be built.
     */
    public function angle(float $value): self
    {
        $this->font->setAngle($value);

        return $this;
    }

    /**
     * Set the maximum width of the text block to be built.
     */
    public function wrap(int $width): self
    {
        $this->font->setWrapWidth($width);

        return $this;
    }
}
