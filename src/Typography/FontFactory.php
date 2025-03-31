<?php

declare(strict_types=1);

namespace Intervention\Image\Typography;

use Closure;
use Intervention\Image\Exceptions\FontException;
use Intervention\Image\Interfaces\FontInterface;

class FontFactory
{
    protected FontInterface $font;

    /**
     * Create new instance
     *
     * @param Closure|FontInterface $init
     * @throws FontException
     * @return void
     */
    public function __construct(callable|Closure|FontInterface $init)
    {
        $this->font = is_a($init, FontInterface::class) ? $init : new Font();

        if (is_callable($init)) {
            $init($this);
        }
    }

    /**
     * Set the filename of the font to be built
     *
     * @param string $value
     * @return FontFactory
     */
    public function filename(string $value): self
    {
        $this->font->setFilename($value);

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
     * Set outline stroke effect for the font to be built
     *
     * @param mixed $color
     * @param int $width
     * @throws FontException
     * @return FontFactory
     */
    public function stroke(mixed $color, int $width = 1): self
    {
        $this->font->setStrokeWidth($width);
        $this->font->setStrokeColor($color);

        return $this;
    }

    /**
     * Set color for the font to be built
     *
     * @param mixed $value
     * @return FontFactory
     */
    public function color(mixed $value): self
    {
        $this->font->setColor($value);

        return $this;
    }

    /**
     * Set the size for the font to be built
     *
     * @param float $value
     * @return FontFactory
     */
    public function size(float $value): self
    {
        $this->font->setSize($value);

        return $this;
    }

    /**
     * Set the horizontal alignment of the font to be built
     *
     * @param string $value
     * @return FontFactory
     */
    public function align(string $value): self
    {
        $this->font->setAlignment($value);

        return $this;
    }

    /**
     * Set the vertical alignment of the font to be built
     *
     * @param string $value
     * @return FontFactory
     */
    public function valign(string $value): self
    {
        $this->font->setValignment($value);

        return $this;
    }

    /**
     * Set the line height of the font to be built
     *
     * @param float $value
     * @return FontFactory
     */
    public function lineHeight(float $value): self
    {
        $this->font->setLineHeight($value);

        return $this;
    }

    /**
     * Set the rotation angle of the font to be built
     *
     * @param float $value
     * @return FontFactory
     */
    public function angle(float $value): self
    {
        $this->font->setAngle($value);

        return $this;
    }

    /**
     * Set the maximum width of the text block to be built
     *
     * @param int $width
     * @return FontFactory
     */
    public function wrap(int $width): self
    {
        $this->font->setWrapWidth($width);

        return $this;
    }

    /**
     * Build font
     *
     * @return FontInterface
     */
    public function __invoke(): FontInterface
    {
        return $this->font;
    }
}
