<?php

declare(strict_types=1);

namespace Intervention\Image\Typography;

use Intervention\Image\Alignment;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\FontInterface;

class FontFactory
{
    protected FontInterface $font;

    /**
     * Create new instance.
     */
    public function __construct(null|callable|FontInterface $font = null)
    {
        $this->font = $font instanceof FontInterface ? clone $font : new Font();

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
    public function filename(string $path): self
    {
        $this->font->setFilepath($path);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see self::filename()
     */
    public function file(string $path): self
    {
        return $this->filename($path);
    }

    /**
     * {@inheritdoc}
     *
     * @see self::filename()
     */
    public function filepath(string $path): self
    {
        return $this->filename($path);
    }

    /**
     * Set outline stroke effect for the font to be built.
     */
    public function stroke(string|ColorInterface $color, int $width = 1): self
    {
        $this->font->setStrokeWidth($width);
        $this->font->setStrokeColor($color);

        return $this;
    }

    /**
     * Set color for the font to be built.
     */
    public function color(string|ColorInterface $color): self
    {
        $this->font->setColor($color);

        return $this;
    }

    /**
     * Set the size for the font to be built.
     */
    public function size(float $size): self
    {
        $this->font->setSize($size);

        return $this;
    }

    /**
     * Set the horizontal and/or vertical alignment of the font.
     */
    public function align(null|string|Alignment $horizontal = null, null|string|Alignment $vertical = null): self
    {
        if ($horizontal) {
            $this->font->setAlignmentHorizontal($horizontal);
        }

        if ($vertical) {
            $this->font->setAlignmentVertical($vertical);
        }

        return $this;
    }

    /**
     * Set the line height of the font to be built.
     */
    public function lineHeight(float $height): self
    {
        $this->font->setLineHeight($height);

        return $this;
    }

    /**
     * Set the clockwise rotation angle of the font to be built.
     */
    public function angle(float $angle): self
    {
        $this->font->setAngle($angle);

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
